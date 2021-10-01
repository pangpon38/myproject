DECLARE @baac_id as INT = 1
DECLARE @chapa_id VARCHAR(30)
DECLARE @count as INT = 0
DECLARE @query nvarchar(max),@del nvarchar(max),@rowcnt INT
DECLARE @baac_db_name nvarchar(max)

SELECT  @count = COUNT(*) FROM [baac_cloud_63].[dbo].[M_BASIC]
WHILE(@baac_id <= (@count -1))
BEGIN
SELECT @chapa_id = CHAPA_CLOUD_ID FROM [baac_cloud_63].[dbo].[M_BASIC] WHERE BASIC_ID = @baac_id
-- SET @baac_db_name = 'SELECT * FROM baac_chapa_'+@chapa_id+'.INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = ''M_CHG_OTHER'''

-- EXEC Sp_executesql @baac_db_name
-- SELECT @rowcnt = @@ROWCOUNT
-- IF @rowcnt > 0
--IF @VariableID IS NULL
-- BEGIN
--     PRINT @chapa_id+'Table Exists'
-- END
SET @del = 'DROP TABLE [baac_chapa_'+@chapa_id+'].[dbo].[M_CHG_OTHER]'
EXEC(@del);

SET @query = 'CREATE TABLE [baac_chapa_'+@chapa_id+'].[dbo].[M_CHG_OTHER] (
[chg_id] int NOT NULL ,
[type_chg_id] int NULL ,
[m_cate_id_old] int NULL ,
[m_cate_id_new] int NULL ,
[baac_aumphur_old] varchar(5) COLLATE Thai_CI_AS NULL ,
[baac_aumphur_new] varchar(5) COLLATE Thai_CI_AS NULL ,
[baac_tambon_old] varchar(10) COLLATE Thai_CI_AS NULL ,
[baac_tambon_new] varchar(10) COLLATE Thai_CI_AS NULL ,
[baac_group_old] varchar(5) COLLATE Thai_CI_AS NULL ,
[baac_group_new] varchar(5) COLLATE Thai_CI_AS NULL ,
[baac_no_old] varchar(10) COLLATE Thai_CI_AS NULL ,
[baac_no_new] varchar(10) COLLATE Thai_CI_AS NULL ,
[baac_cif_old] varchar(50) COLLATE Thai_CI_AS NULL ,
[baac_cif_new] varchar(50) COLLATE Thai_CI_AS NULL ,
[approve_status] int NULL ,
[approve_date] date NULL ,
[create_by] varchar(100) COLLATE Thai_CI_AS NULL ,
[update_by] varchar(100) COLLATE Thai_CI_AS NULL ,
[create_datetime] datetime NULL ,
[update_datetime] datetime NULL ,
[chg_oth_id] int NOT NULL IDENTITY(1,1) ,
CONSTRAINT [PK__M_CHG_OT_'+@chapa_id+'] PRIMARY KEY ([chg_oth_id])
)
ON [PRIMARY]'

EXEC(@query)

SET @baac_id = @baac_id +1

END