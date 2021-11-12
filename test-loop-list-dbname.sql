DECLARE @SQLString NVARCHAR(MAX)
DECLARE @DBNAME NVARCHAR(MAX)
DECLARE @DBExec NVARCHAR(MAX)

SET @SQLString=N''
-- SET @DBNAME=N''

DECLARE cursor_name CURSOR FOR 
		SELECT
	name
FROM
	master.sys.databases
WHERE
	name NOT IN (
		'master',
		'tempdb',
		'model',
		'msdb',
		'baac_cloud_63',
		'baac_chapa_dev',
		'baac_chapa_000_20210617'
	)
    ORDER BY name ASC

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @DBNAME

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBExec = @DBNAME + N'.sys.sp_executesql';

SET @SQLString = 'ALTER VIEW V_REPORT_104_1
AS 
SELECT     ROW_NUMBER() OVER (ORDER BY member_id ASC) AS member_pk, *
FROM         (SELECT     a.member_resign_id, '''' AS member_leave_id, a.member_id, a.resign_date, b.m_cate_name, b.member_no, a.money_remain, b.saving_money, a.resign_count,
                                              a.app_resign_date AS app_date, ''6'' AS member_status , b.prefix_name,b.id_card_no, b.fname, b.lname, a.mem_resign_status AS approve_status, 2 AS status_out, ''ลาออก'' AS status_out_name,
                                              a.type_resign_desc AS type_desc, a.app_resign_date AS retired_date
                       FROM          M_MEMBER_RESIGN a INNER JOIN
                                              V_MEMBER b ON b.member_id = a.member_id AND b.member_status = ''6''
                       WHERE      a.mem_resign_status = ''1''
                       UNION ALL
                       SELECT     '''' AS member_resign_id, a.member_leave_id, a.member_id, a.leave_date, b.m_cate_name, b.member_no, a.money_remain, b.saving_money, a.leave_count, a.leave_date AS app_date,
                                             ''3'' AS member_status, b.prefix_name,b.id_card_no, b.fname, b.lname, a.mem_leave_status AS approve_status, 1 AS status_out, ''คัดชื่อออก'' AS status_out_name, a.type_leave_desc AS type_desc,
                                             a.leave_date AS retired_date
                       FROM         M_MEMBER_LEAVE a INNER JOIN
                                             V_MEMBER b ON b.member_id = a.member_id AND b.member_status = ''3''
                       WHERE     a.mem_leave_status = ''1'' ) AS tb'


		EXEC @DBExec @SQLString


				FETCH NEXT FROM cursor_name
		INTO @DBNAME
	END
	

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name