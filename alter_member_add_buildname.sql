DECLARE
	@SQLString NVARCHAR (MAX) DECLARE
		@DBNAME NVARCHAR (MAX) DECLARE
			@DBExec NVARCHAR (MAX)
		SET @SQLString = N'' -- SET @DBNAME=N''
		DECLARE
			cursor_name CURSOR FOR SELECT
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
			ORDER BY
				name ASC OPEN cursor_name FETCH NEXT
			FROM
				cursor_name INTO @DBNAME -- Loop From Cursor
			WHILE (@@FETCH_STATUS = 0)
			BEGIN

			SET @DBExec = @DBNAME + N'.sys.sp_executesql' ;
			SET @SQLString = 'IF NOT EXISTS (
  SELECT
    *
  FROM
    INFORMATION_SCHEMA.COLUMNS
  WHERE
    TABLE_NAME = ''M_MEMBER'' AND COLUMN_NAME = ''building_name'')
BEGIN
  ALTER TABLE M_MEMBER ADD building_name VARCHAR(255)
  print ''' +@DBNAME + '''
END' EXEC @DBExec @SQLString 

--==================================================

			SET @SQLString = 'IF NOT EXISTS (
  SELECT
    *
  FROM
    INFORMATION_SCHEMA.COLUMNS
  WHERE
    TABLE_NAME = ''M_MEMBER'' AND COLUMN_NAME = ''building_name2'')
BEGIN
  ALTER TABLE M_MEMBER ADD building_name2 VARCHAR(255)
  print ''' +@DBNAME + 'building_name2''
END' EXEC @DBExec @SQLString FETCH NEXT
			FROM
				cursor_name INTO @DBNAME
			END -- Close cursor
			CLOSE cursor_name DEALLOCATE cursor_name