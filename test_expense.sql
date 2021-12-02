ALTER PROCEDURE [dbo].[test_expense]
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
DECLARE @total_all FLOAT
DECLARE @total_money_expense FLOAT
DECLARE @total_money_expense2 FLOAT
DECLARE @total_buildcost FLOAT
DECLARE @dr_sum_1 FLOAT
DECLARE @cr_sum_1 FLOAT
DECLARE @dr_sum_2 FLOAT
DECLARE @cr_sum_2 FLOAT
DECLARE @s_date VARCHAR(10)
DECLARE @e_date VARCHAR(10)
DECLARE @quater INT

SET @SQLString=N''
SET @date_run = convert(VARCHAR(10), getdate(), 23)
SET @date_data = convert(VARCHAR(10), getdate()-1, 23)
DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID <> '000' AND DEP_STATUS = '1' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID
SET @maxid = (SELECT ISNULL(MAX(REPORT_ID),0) FROM M_REPORT_EXPENSE)
SET @quater = 1

SET @s_date = convert(VARCHAR(10), CONCAT(YEAR(Getdate()),'-01-01'), 23)

-- --เงินสด

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''28'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''28'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT

SET @ParameterDef='@dr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''28'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_2 OUTPUT

SET @ParameterDef='@cr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''28'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_cash = abs((@dr_sum_1+@dr_sum_2))-abs((@cr_sum_1+@cr_sum_2))
------------

-- --เงินฝากธนาคาร

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'SELECT
	@dr_sum_1=ISNULL(SUM (debit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE acc_gl_parent_id = ''7'')
AND tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(credit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE acc_gl_parent_id = ''7'')
AND tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT

SET @ParameterDef='@dr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE acc_gl_parent_id = ''7'')
and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_2 OUTPUT

SET @ParameterDef='@cr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_2=ISNULL(sum(credit_value),0)
FROM
	'+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b ON a.project_id = b.project_id
WHERE
	acc_gl_id IN(SELECT acc_gl_id FROM '+@DBNAME+'.dbo.ACC_GL WHERE acc_gl_parent_id = ''7'')
and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_bank = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --เงินสงเคราะห์ล่วงหน้า

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''40'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''40'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT

SET @ParameterDef='@dr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''40'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_2 OUTPUT

SET @ParameterDef='@cr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''40'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_money_expense = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --เงินสงเคราะห์ค้างจ่าย

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''42'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''42'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT

SET @ParameterDef='@dr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_2=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''42'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_2 OUTPUT

SET @ParameterDef='@cr_sum_2 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_2=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''42'' and tran_date between ''2021-01-01'' and ''2021-11-30'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_money_expense2 = (@dr_sum_1+@dr_sum_2)-(@cr_sum_1+@cr_sum_2)
------------

-- --อุปกรณ์สำนักงาน

SET @ParameterDef='@dr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @dr_sum_1=ISNULL(sum(debit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''34'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@dr_sum_1 OUTPUT

SET @ParameterDef='@cr_sum_1 FLOAT OUTPUT'
SET @SQLString = 'select @cr_sum_1=ISNULL(sum(credit_value),0)
from '+@DBNAME+'.dbo.V_ACC_TRANSEC_DETAIL a 
LEFT JOIN '+@DBNAME+'.dbo.bdg_project b on a.project_id =b.project_id where acc_gl_id = ''37'' and tran_date < ''2021-01-01'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_1 OUTPUT
SET @total_buildcost = (@dr_sum_1-@cr_sum_1)
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
AND ACC_TRANSEC.tran_date BETWEEN ''2021-01-01''
AND ''2021-11-30''
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
AND ACC_TRANSEC.tran_date BETWEEN ''2021-01-01''
AND ''2021-11-30''
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
AND ACC_TRANSEC.tran_date BETWEEN ''2021-01-01''
AND ''2021-11-30''
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
AND ACC_TRANSEC.tran_date BETWEEN ''2021-01-01''
AND ''2021-11-30''
AND ACC_TRANSEC.delete_flag = ''0''
AND ACC_GL.gl_code LIKE ''5%''
AND post_type = ''C'''
EXEC sp_Executesql @SQLString,@ParameterDef,@cr_sum_2 OUTPUT
SET @total_all = abs((@dr_sum_1-@dr_sum_2))-abs((@cr_sum_1-@cr_sum_2))
------------------

-- SET IDENTITY_INSERT M_REPORT_EXPENSE ON

INSERT INTO M_REPORT_EXPENSE (
DATE_RUN,
DATE_DATA,
CHAPA_CLOUD_ID,
total_cash,
total_bank,
total_money_expense,
total_money_expense2,
total_buildcost,
total_all
) 
VALUES(
@date_run,
@date_data,
@CHAPA_CLOUD_ID,
CONVERT(numeric(16,2),CAST(@total_cash AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_bank AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_money_expense AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_money_expense2 AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_buildcost AS FLOAT)),
CONVERT(numeric(16,2),CAST(@total_all AS FLOAT))
)



-- SET IDENTITY_INSERT M_REPORT_EXPENSE OFF

		FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name
END