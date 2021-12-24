ALTER PROCEDURE [dbo].[report_expense]
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
DECLARE @ParameterDef2 NVARCHAR(500)
DECLARE @total_cash FLOAT
DECLARE @total_bank FLOAT
DECLARE @total_other FLOAT
DECLARE @total_all FLOAT
DECLARE @total_money_expense FLOAT
DECLARE @total_money_expense1 FLOAT
DECLARE @total_money_expense2 FLOAT
DECLARE @total_buildcost FLOAT
DECLARE @total_debt_other FLOAT
DECLARE @total_amount_all FLOAT
DECLARE @dr_sum_1 FLOAT
DECLARE @cr_sum_1 FLOAT
DECLARE @dr_sum_2 FLOAT
DECLARE @cr_sum_2 FLOAT
DECLARE @s_date VARCHAR(10)
DECLARE @e_date VARCHAR(10)
DECLARE @quater INT
DECLARE @month VARCHAR(100)
DECLARE @S_YEAR INT

SET @SQLString=N''
SET @date_run = convert(VARCHAR(10), getdate(), 23)
SET @date_data = convert(VARCHAR(10), getdate()-1, 23)
SET @S_YEAR =CONVERT(VARCHAR(4),YEAR(GETDATE()))
SET @month = CONVERT(VARCHAR(10),MONTH(GETDATE()))
SET @quater = 1

--เช็คไตรมาสที่รัน PROCEDURE
IF(@month >=1 and @month <= 3)
BEGIN
SET @quater = 1
END
ELSE IF(@month >=4 and @month <= 6)
BEGIN
SET @quater = 2
END
ELSE IF(@month >=6 and @month <= 9)
BEGIN
SET @quater = 3
END
ELSE IF(@month >=10 and @month <= 12)
BEGIN
SET @quater = 4
END

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID = '001' AND DEP_STATUS = '1' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_REPORT_EXPENSE)

SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-01-01'), 23)
SET @e_date = convert(VARCHAR(10), getdate(), 23)

--set param output--
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @ParameterDef2='@dr_sum_2 FLOAT OUTPUT,@cr_sum_2 FLOAT OUTPUT'

-- --เงินสด

SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''1001'' ) 
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0),
                         @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''1001'' )  
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_cash = abs((@dr_sum_1+@dr_sum_2))-abs((@cr_sum_1+@cr_sum_2))
------------

-- --เงินฝากธนาคาร

SET @SQLString = 'SELECT
	@dr_sum_1=ISNULL(SUM (debit_value),0),
	@cr_sum_1=ISNULL(sum(credit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code LIKE ''11%'' )
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select 
    @dr_sum_2=ISNULL(sum(debit_value),0),
    @cr_sum_2=ISNULL(sum(credit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code LIKE ''11%'' )
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_bank = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --สินทรัพย์อื่นๆ
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id IN (SELECT ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code like ''12%'' or gl_code like ''13%'' ) 
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0),
                         @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id IN (SELECT ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code like ''12%'' or gl_code like ''13%'' )   
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_other = abs((@dr_sum_1+@dr_sum_2))-abs((@cr_sum_1+@cr_sum_2))

-- --เงินสงเคราะห์ล่วงหน้า

SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2001'' ) 
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select     
    @dr_sum_2=ISNULL(sum(debit_value),0),
    @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2001'' )  
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_money_expense = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --หนี้สินอื่นๆ
SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id IN (SELECT ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code like ''2%'' and gl_code not in(''2001'',''2002'',''2003''))  
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select 
    @dr_sum_2=ISNULL(sum(debit_value),0),
    @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id IN (SELECT ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code like ''2%'' and gl_code not in(''2001'',''2002'',''2003''))
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_debt_other = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --เงินสงเคราะห์

SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2002'' )  
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select 
    @dr_sum_2=ISNULL(sum(debit_value),0),
    @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2002'' ) 
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_money_expense1 = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --เงินสงเคราะห์ค้างจ่าย

SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2003'' )  
and tran_date < '''+ @s_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @SQLString = 'select 
    @dr_sum_2=ISNULL(sum(debit_value),0),
    @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''2003'' ) 
and tran_date between '''+ @s_date+''' and '''+@e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef2,@dr_sum_2 OUTPUT,@cr_sum_2 OUTPUT

SET @total_money_expense2 = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --อาคารและอุปกรณ์เครื่องใช้สำนักงาน

SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id IN (SELECT ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code like ''14%'' ) 
and tran_date < '''+ @e_date+''''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @total_buildcost = (@dr_sum_1-@cr_sum_1)
------------

-- --รายได้สูงกว่าค่าใช้จ่ายสะสม

SET @SQLString = 'select 
    @dr_sum_1=ISNULL(sum(debit_value),0),
    @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id 
where acc_gl_id = (SELECT top 1 ISNULL(acc_gl_id,0) FROM '+@DBNAME+'.dbo.ACC_GL WHERE gl_code = ''3001'' )'
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @total_amount_all = @cr_sum_1
------------

-- --รายได้สูง(ต่ำ)กว่ารายจ่าย

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(post_value),0)
FROM
	'+@DBNAME+'.dbo.ACC_GL
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC_DETAIL ON ACC_GL.acc_gl_id = ACC_TRANSEC_DETAIL.acc_gl_id
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC ON ACC_TRANSEC.tran_id = ACC_TRANSEC_DETAIL.tran_id
WHERE
	1 = 1
AND ACC_TRANSEC.tran_date between '''+ @s_date+'''
and '''+@e_date+'''
AND ACC_TRANSEC.delete_flag = ''0''
AND ACC_GL.gl_code LIKE ''4%''
AND post_type = ''D'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(post_value),0)
FROM
	'+@DBNAME+'.dbo.ACC_GL
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC_DETAIL ON ACC_GL.acc_gl_id = ACC_TRANSEC_DETAIL.acc_gl_id
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC ON ACC_TRANSEC.tran_id = ACC_TRANSEC_DETAIL.tran_id
WHERE
	1 = 1
AND ACC_TRANSEC.tran_date between '''+ @s_date+'''
and '''+@e_date+'''
AND ACC_TRANSEC.delete_flag = ''0''
AND ACC_GL.gl_code LIKE ''4%''
AND post_type = ''C'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT

SET @ParameterDef='@dr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_2=ISNULL(sum(post_value),0)
FROM
	'+@DBNAME+'.dbo.ACC_GL
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC_DETAIL ON ACC_GL.acc_gl_id = ACC_TRANSEC_DETAIL.acc_gl_id
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC ON ACC_TRANSEC.tran_id = ACC_TRANSEC_DETAIL.tran_id
WHERE
	1 = 1
AND ACC_TRANSEC.tran_date between '''+ @s_date+'''
and '''+@e_date+'''
AND ACC_TRANSEC.delete_flag = ''0''
AND ACC_GL.gl_code LIKE ''5%''
AND post_type = ''D'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_2 OUTPUT

SET @ParameterDef='@cr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_2=ISNULL(sum(post_value),0)
FROM
	'+@DBNAME+'.dbo.ACC_GL
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC_DETAIL ON ACC_GL.acc_gl_id = ACC_TRANSEC_DETAIL.acc_gl_id
JOIN '+@DBNAME+'.dbo.ACC_TRANSEC ON ACC_TRANSEC.tran_id = ACC_TRANSEC_DETAIL.tran_id
WHERE
	1 = 1
AND ACC_TRANSEC.tran_date between '''+ @s_date+'''
and '''+@e_date+'''
AND ACC_TRANSEC.delete_flag = ''0''
AND ACC_GL.gl_code LIKE ''5%''
AND post_type = ''C'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_all = abs((@cr_sum_1-@cr_sum_2)) - abs((@dr_sum_1-@dr_sum_2))
------------------

-- SET IDENTITY_INSERT M_REPORT_EXPENSE ON

INSERT INTO M_REPORT_EXPENSE (
DATE_RUN,
DATE_DATA,
CHAPA_CLOUD_ID,
total_cash,
total_bank,
total_money_expense,
total_money_expense1,
total_money_expense2,
total_buildcost,
total_all,
total_other,
total_debt_other,
total_amount_all,
quater,
year_expense
) 
VALUES(
@date_run,
@date_data,
@CHAPA_CLOUD_ID,
CONVERT(numeric(16,2),CAST(@total_cash AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_bank AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_money_expense AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_money_expense1 AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_money_expense2 AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_buildcost AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_all AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_other AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_debt_other AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_amount_all AS FLOAT)),
@quater,
@S_YEAR
)



-- SET IDENTITY_INSERT M_REPORT_EXPENSE OFF

		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name

	--- ลบข้อมูลออกจาก quater ก่อนหน้า
	SET @SQLString ='DELETE FROM M_REPORT_EXPENSE WHERE quater = '''+convert(VARCHAR(10),@quater)+''' 
	AND DATE_RUN <> '''+convert(VARCHAR(10),@date_run)+''' AND year_expense =  '''+convert(VARCHAR(10),@S_YEAR)+''' '
	EXEC sp_Executesql @SQLString
END