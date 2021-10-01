DECLARE @SQLString NVARCHAR(MAX)
DECLARE @BASIC_ID INT
DECLARE @CHAPA_CLOUD_ID VARCHAR(3)
DECLARE @DBNAME VARCHAR(100)
DECLARE @MAX_YEAR VARCHAR(100)

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

SET @MAX_YEAR = (SELECT MAX(year_work) FROM sys_setup where active_status = 1)

SET @SQLString = 'update '+@DBNAME+'.dbo.sys_setup set dead_number = (select count(dead_number) c_member from '+@DBNAME+'.dbo.M_MEMBER_OUT where year_work = '+@MAX_YEAR+')
WHERE year_work = '+@MAX_YEAR+' AND active_status = 1'


		EXEC sp_Executesql @SQLString 

				FETCH NEXT FROM cursor_name
		INTO @BASIC_ID,@CHAPA_CLOUD_ID
	END
	

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name