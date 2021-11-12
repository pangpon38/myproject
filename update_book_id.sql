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

SET @SQLString = 'UPDATE F_RECEIPT
SET book_id = (
	SELECT
		top 1 book_de_id
	FROM
		F_BOOK_DE
	WHERE
		rec_id = F_RECEIPT.rec_id
)
WHERE
	book_id IS NULL'

    EXEC @DBExec @SQLString


				FETCH NEXT FROM cursor_name
		INTO @DBNAME
	END
	

	-- Close cursor
	CLOSE cursor_name
	DEALLOCATE cursor_name