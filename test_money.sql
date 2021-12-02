ALTER PROCEDURE [dbo].[test_money]
AS
BEGIN
DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME VARCHAR(100)
DECLARE @maxid VARCHAR(50)
DECLARE @date_run VARCHAR(10)
DECLARE @date_data VARCHAR(10)
DECLARE @ParameterDef NVARCHAR(500)
DECLARE @c_member_1 VARCHAR(100)
DECLARE @s_money_1 FLOAT
DECLARE @c_member_2 VARCHAR(100)
DECLARE @s_money_2 FLOAT
DECLARE @c_member_3 VARCHAR(100)
DECLARE @s_money_3 FLOAT
DECLARE @s_date VARCHAR(10)
DECLARE @e_date VARCHAR(10)

SET @SQLString=N''
SET @date_run = convert(VARCHAR(10), getdate(), 23)
SET @date_data = convert(VARCHAR(10), getdate()-1, 23)

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID = '000' AND DEP_STATUS = '1' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_REPORT_MONEY)

SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-01-01'), 23)

-- --เงินสงเคราะห์ค้างชำระ

SET @ParameterDef='@c_member_1 VARCHAR(100) OUTPUT'
SET @SQLString = 'SELECT top 1 @c_member_1 = ISNULL(num_type2,0) FROM '+@DBNAME+'.dbo.W_MEMBER_MONEY 
WHERE dailydate <= ''2021-12-31'' order by dailydate desc'
EXEC sp_Executesql @SQLString,@ParameterDef,@c_member_1=@c_member_1 OUTPUT

SET @ParameterDef='@s_money_1 FLOAT OUTPUT'
SET @SQLString = 'SELECT top 1 @s_money_1 = ISNULL(money_type2,0) FROM '+@DBNAME+'.dbo.W_MEMBER_MONEY 
WHERE dailydate <= ''2021-12-31'' order by dailydate desc'
EXEC sp_Executesql @SQLString,@ParameterDef,@s_money_1=@s_money_1 OUTPUT

-- --เงินสงเคราะห์จ่ายแล้วทั้งสิ้น

SET @ParameterDef='@c_member_2 VARCHAR(100) OUTPUT'
SET @SQLString = 'SELECT @c_member_2=ISNULL(COUNT(DISTINCT M_MEMBER_OUT.member_id),0)
 FROM '+@DBNAME+'.dbo.M_MEMBER_OUT 
 LEFT JOIN '+@DBNAME+'.dbo.F_PAYMENT ON F_PAYMENT.member_id = M_MEMBER_OUT.member_id 
 LEFT JOIN '+@DBNAME+'.dbo.F_PAYMENT_DE ON F_PAYMENT.payment_id = F_PAYMENT_DE.payment_id 
 and F_PAYMENT.payment_type = 1 and F_PAYMENT.PERIOD_PAID > 0 where M_MEMBER_OUT.request_date >='''+@s_date+''' 
 AND M_MEMBER_OUT.request_date <=''2021-12-31'' and F_PAYMENT_DE.cost_id in (''22'',''33'' )'

EXEC sp_Executesql @SQLString,@ParameterDef,@c_member_2=@c_member_2 OUTPUT

SET @ParameterDef='@s_money_2 FLOAT OUTPUT'
SET @SQLString = 'SELECT @s_money_2=ISNULL(SUM(F_PAYMENT_DE.money),0)
 FROM '+@DBNAME+'.dbo.M_MEMBER_OUT 
 LEFT JOIN '+@DBNAME+'.dbo.F_PAYMENT ON F_PAYMENT.member_id = M_MEMBER_OUT.member_id 
 LEFT JOIN '+@DBNAME+'.dbo.F_PAYMENT_DE ON F_PAYMENT.payment_id = F_PAYMENT_DE.payment_id 
 and F_PAYMENT.payment_type = 1 and F_PAYMENT.PERIOD_PAID > 0 where M_MEMBER_OUT.request_date >='''+@s_date+''' 
 AND M_MEMBER_OUT.request_date <=''2021-12-31'' and F_PAYMENT_DE.cost_id in (''22'',''33'' )'

EXEC sp_Executesql @SQLString,@ParameterDef,@s_money_2=@s_money_2 OUTPUT

SET @ParameterDef='@c_member_3 VARCHAR(100) OUTPUT'
SET @SQLString = 'select @c_member_3=COUNT(DISTINCT M_ARREAR.member_id) from '+@DBNAME+'.dbo.M_ARREAR 
where M_ARREAR.member_out_id in ( select M_MEMBER_OUT.member_out_id from '+@DBNAME+'.dbo.M_MEMBER_OUT where 
M_MEMBER_OUT.request_date >='''+@s_date+''' AND M_MEMBER_OUT.request_date <=''2021-12-31'' ) 
and M_ARREAR.STATUS_PAID_DIED = 0 and M_ARREAR.FLAG_PAID = 1'
EXEC sp_Executesql @SQLString,@ParameterDef,@c_member_3=@c_member_3 OUTPUT

SET @ParameterDef='@s_money_3 FLOAT OUTPUT'
SET @SQLString = 'select @s_money_3=SUM(M_ARREAR.ARREAR_MONEY) from '+@DBNAME+'.dbo.M_ARREAR 
where M_ARREAR.member_out_id in ( select M_MEMBER_OUT.member_out_id from '+@DBNAME+'.dbo.M_MEMBER_OUT where 
M_MEMBER_OUT.request_date >='''+@s_date+''' AND M_MEMBER_OUT.request_date <=''2021-12-31'' ) 
and M_ARREAR.STATUS_PAID_DIED = 0 and M_ARREAR.FLAG_PAID = 1'
EXEC sp_Executesql @SQLString,@ParameterDef,@s_money_3=@s_money_3 OUTPUT

INSERT INTO M_REPORT_MONEY (
DATE_RUN,
DATE_DATA,
CHAPA_CLOUD_ID,
c_member_1,
s_money_1,
c_member_2,
s_money_2,
c_member_3,
s_money_3
)VALUES(
@date_run,
@date_data,
@CHAPA_CLOUD_ID,
@c_member_1,
CONVERT(numeric(16,2),CAST(@s_money_1 AS FLOAT)),
@c_member_2,
CONVERT(numeric(16,2),CAST(@s_money_2 AS FLOAT)),
@c_member_3,
CONVERT(numeric(16,2),CAST(@s_money_3 AS FLOAT))
)

		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name
END