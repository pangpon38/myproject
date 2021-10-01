DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME VARCHAR(100)

SET @SQLString=N''

DECLARE cursor_name CURSOR FOR 
	SELECT BASIC_ID,CHAPA_CLOUD_ID FROM baac_cloud_63.dbo.M_BASIC WHERE CHAPA_CLOUD_ID <> '000' ORDER BY CHAPA_CLOUD_ID

	OPEN cursor_name
	FETCH NEXT FROM cursor_name
	INTO @BASIC_ID,@CHAPA_CLOUD_ID

	-- Loop From Cursor
	WHILE (@@FETCH_STATUS = 0) 
	BEGIN 

SET @DBNAME='baac_chapa_'+@CHAPA_CLOUD_ID

SET @SQLString = 'UPDATE '+@DBNAME+'.dbo.M_MEMBER
SET leave_date = (
	SELECT
		TOP 1 dead_date
	FROM
		'+@DBNAME+'.dbo.M_MEMBER_OUT
	WHERE
		member_id = M_MEMBER.member_id
	AND mem_out_status = 1
	ORDER BY
		member_out_id DESC
)
WHERE
	member_status = 2
AND leave_date IS NULL 
OR leave_date != (SELECT
		TOP 1 dead_date
	FROM
		'+@DBNAME+'.dbo.M_MEMBER_OUT
	WHERE
		member_id = M_MEMBER.member_id
	AND mem_out_status = 1
	ORDER BY
		member_out_id DESC)'

		EXEC sp_Executesql @SQLString 

				FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END
	

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name