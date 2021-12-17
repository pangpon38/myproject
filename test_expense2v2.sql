ALTER PROCEDURE [dbo].[test_expense2]
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
DECLARE @condition VARCHAR(500)
-- --รายได้
DECLARE @money_regis FLOAT
DECLARE @money_maintian FLOAT
DECLARE @money_expense FLOAT
DECLARE @money_interest FLOAT
DECLARE @money_donate FLOAT
DECLARE @money_other FLOAT
----------

-- --รายจ่าย
DECLARE @money_salary FLOAT
DECLARE @money_allowance FLOAT
DECLARE @money_vehicle FLOAT
DECLARE @money_uniform FLOAT
DECLARE @money_meeting FLOAT
DECLARE @money_train FLOAT
DECLARE @money_building FLOAT
DECLARE @money_wreath FLOAT
DECLARE @cost_other FLOAT
DECLARE @money_utility FLOAT
DECLARE @money_account FLOAT
DECLARE @money_tax FLOAT
DECLARE @money_cost_building FLOAT
DECLARE @money_allowance2 FLOAT
DECLARE @money_vehicle2 FLOAT
DECLARE @money_repair FLOAT
DECLARE @money_guarantee FLOAT
----------

DECLARE @dr_sum_1 FLOAT
DECLARE @cr_sum_1 FLOAT
DECLARE @s_date VARCHAR(10)
DECLARE @e_date VARCHAR(10)
DECLARE @quater INT
DECLARE @month VARCHAR(100)
DECLARE @S_YEAR VARCHAR(4)

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
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_REPORT_EXPENSE2)
SET @quater = 1

SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-01-01'), 23)
SET @S_YEAR =CONVERT(VARCHAR(4),YEAR(GETDATE()))
SET @month = CONVERT(VARCHAR(10),MONTH(GETDATE()))

IF(@month >=1 and @month <= 3)
BEGIN
SET @quater = 1
SET @condition = 'and (MONTH(tran_date) between ''01'' and ''03'') AND YEAR(tran_date) = '''+@S_YEAR+''''
END
ELSE IF(@month >=4 and @month <= 6)
BEGIN
SET @quater = 2
SET @condition = 'and (MONTH(tran_date) between ''04'' and ''06'') AND YEAR(tran_date) = '''+@S_YEAR+''''
END
ELSE IF(@month >=6 and @month <= 9)
BEGIN
SET @quater = 3
SET @condition = 'and (MONTH(tran_date) between ''07'' and ''09'') AND YEAR(tran_date) = '''+@S_YEAR+''''
END
ELSE IF(@month >=10 and @month <= 12)
BEGIN
SET @quater = 4
SET @condition = 'and (MONTH(tran_date) between ''10'' and ''12'') AND YEAR(tran_date) = '''+@S_YEAR+''''
END

-- -- รายได้-------------------------------------------------

-- --ค่าสมัคร
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_regis = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าบำรุง
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_maintian = abs(@dr_sum_1-@cr_sum_1)

--เงินหักจากเงินสงเคราะห์
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_expense = abs(@dr_sum_1 - @cr_sum_1)

-- --ดอกเบี้ยเงินฝากธนาคาร
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_interest = abs(@dr_sum_1 - @cr_sum_1)

-- --เงินบริจาค
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_donate = abs(@dr_sum_1 - @cr_sum_1)

-- --รายได้อื่นๆ
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id NOT IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_other = abs(@dr_sum_1 - @cr_sum_1)

-- -- รายจ่าย-------------------------------------------------

-- --เงินเดือน ค่าจ้าง
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_salary = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าเบี้ยประชุม กรรมการ
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_allowance = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าพาหนะกรรมการ
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_vehicle = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าเครื่องแบบเจ้าหน้าที่
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_uniform = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าใช้จ่ายประชุมใหญ่
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_meeting = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าลงทะเบียนในการเข้าร่วมประชุมฝึกอบรม
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_train = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าเช่าอาคารที่ดิน
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_building = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าใช้จ่ายในการจัดซื้อที่ดิน อาคาร และครุภัณฑ์ต่าง ๆ
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_cost_building = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าทำบัญชี
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_account = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าพวงรีด
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_wreath = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าใช้จ่ายสาธารณูปโภค
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_utility = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าภาษีเงินได้
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_tax = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าพาหนะเบี้ยเลี้ยง-เจ้าหน้าที่
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_allowance2 = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าพาหนะ ค่าเช่าที่พัก เจ้าหน้าที่
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_vehicle2 = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าซ่อมแซมอาคาร
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_repair = abs(@dr_sum_1 - @cr_sum_1)

-- --ประกันสังคม-เจ้าหน้าที่
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @money_guarantee = abs(@dr_sum_1 - @cr_sum_1)

-- --ค่าใช้จ่ายอื่นๆ
SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT,@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0),
                         @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a where acc_gl_id NOT IN (
    SELECT
	acc_gl_id
FROM
	'+@DBNAME+'.dbo.ACC_INCOME
WHERE
	income_code = ''001'') and a.acc_close = ''0'' 
'+@condition+''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT,@cr_sum_1 OUTPUT

SET @cost_other = abs(@dr_sum_1 - @cr_sum_1)

-- SET IDENTITY_INSERT M_REPORT_EXPENSE2 ON

INSERT INTO M_REPORT_EXPENSE2 (
DATE_RUN,
DATE_DATA,
CHAPA_CLOUD_ID,
money_regis,
money_maintian,
money_expense,
money_interest,
money_donate,
money_other,
money_salary,
money_allowance,
money_vehicle,
money_uniform,
money_meeting,
money_train,
money_building,
money_wreath,
cost_other,
money_allowance2,
money_vehicle2,
money_repair,
money_tax, 
money_utility,
money_cost_building,
money_account,
money_guarantee,
quater,
year_expense2
)VALUES (
@date_run,
@date_data,
@CHAPA_CLOUD_ID,
CONVERT(numeric(16,2),CAST(@money_regis AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_maintian AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_expense AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_interest AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_donate AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_other AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_salary AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_allowance AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_vehicle AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_uniform AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_meeting AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_train AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_building AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_wreath AS FLOAT)),
CONVERT(numeric(16,2),CAST(@cost_other AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_allowance2 AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_vehicle2 AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_repair AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_tax AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_utility AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_cost_building AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_account AS FLOAT)),
CONVERT(numeric(16,2),CAST(@money_guarantee AS FLOAT)),
@quater,
@S_YEAR
)

-- SET IDENTITY_INSERT M_REPORT_EXPENSE2 OFF
		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name

		--- ลบข้อมูลออกจาก quater ก่อนหน้า
	SET @SQLString ='DELETE FROM M_REPORT_EXPENSE2 WHERE quater = '''+convert(VARCHAR(10),@quater)+''' 
	AND DATE_RUN <> '''+convert(VARCHAR(10),@date_run)+''' AND year_expense2 =  '''+convert(VARCHAR(10),@S_YEAR)+''' '
	EXEC sp_Executesql @SQLString
END