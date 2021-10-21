SELECT
	BASIC_ID,
	CHAPA_CLOUD_ID
FROM
	baac_cloud_63.dbo.M_BASIC
WHERE
	CHAPA_CLOUD_ID IN (
		'094',
		'097',
		'102',
		'116',
		'128',
		'137',
		'138',
		'141',
		'142',
		'144',
		'149',
		'151',
		'153',
		'155',
		'156',
		'175',
		'176',
		'179',
		'182',
		'206',
		'227',
		'234',
		'244',
		'245',
		'246',
		'998'
	)
ORDER BY
	CHAPA_CLOUD_ID


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
	);