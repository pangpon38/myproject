--แบบที่ 2

SELECT SUM(tb.COUNTS) as total_mem,tb.PROVINCE_CODE,tb.AGE_RANGE,tb.TYPE_STATUS
FROM (SELECT
	b.PROVINCE_CODE,
	COUNTS,
  a.TYPE_STATUS,
  a.AGE_RANGE
FROM
	M_AGE_RANGE a
LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID) as tb
GROUP BY tb.PROVINCE_CODE,tb.AGE_RANGE,tb.TYPE_STATUS
ORDER BY tb.PROVINCE_CODE ASC

-- แบบที่ 3

SELECT SUM(tb.COUNTS) as total_mem,tb.REGION_ID,tb.AGE_RANGE,tb.TYPE_STATUS
FROM (SELECT
	b.REGION_ID,
	COUNTS,
  a.TYPE_STATUS,
  a.AGE_RANGE
FROM
	M_AGE_RANGE a
LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID) as tb
GROUP BY tb.REGION_ID,tb.AGE_RANGE,tb.TYPE_STATUS
ORDER BY tb.REGION_ID ASC

--แบบที่ 5

SELECT
        SUM (tb.COUNTS) AS total_member,
        tb.OFFICE_ID,
        c.OFFICE_NAME,
        tb.GENDER
    FROM
        (
            SELECT
                b.REGION_ID,
                b.OFFICE_ID,
                b.BR_ID,
                COUNTS,
                a.CHAPA_CLOUD_ID,
                a.GENDER,
                a.AGE_RANGE
            FROM
                M_AGE_RANGE a
            LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID
        ) AS tb
    LEFT JOIN M_OFFICE c ON tb.OFFICE_ID = c.OFFICE_ID
    WHERE
        1 = 1
    GROUP BY
        tb.OFFICE_ID,
        tb.GENDER,
        c.OFFICE_NAME
    ORDER BY
        tb.OFFICE_ID,
        tb.GENDER ASC

--แบบที่ 6

SELECT
        SUM (tb.COUNTS) AS total_member,
        tb.REGION_ID,
        c.REGION_NAME,
        tb.GENDER
    FROM
        (
            SELECT
                b.REGION_ID,
                b.OFFICE_ID,
                b.BR_ID,
                COUNTS,
                a.CHAPA_CLOUD_ID,
                a.GENDER,
                a.AGE_RANGE
            FROM
                M_AGE_RANGE a
            LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID
        ) AS tb
    LEFT JOIN M_REGION c ON tb.REGION_ID = c.REGION_ID
    WHERE
        1 = 1
    GROUP BY
        tb.REGION_ID,
        tb.GENDER,
        c.REGION_NAME
    ORDER BY
        tb.REGION_ID,
        tb.GENDER ASC

--แบบที่ 7

        SELECT
	SUM (tb.COUNTS) AS total_member,
	tb.REGION_ID,
	e.REGION_NAME,
	tb.TYPE_MEMBER
FROM
	(
		SELECT
			b.OFFICE_ID,
			b.BR_ID,
			b.REGION_ID,
			b.BASIC_ID,
			b.DEP_TYPE,
			COUNTS,
			a.TYPE_MEMBER
		FROM
			M_AGE_RANGE a
		LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID
	) AS tb
LEFT JOIN M_REGION e ON tb.REGION_ID = e.REGION_ID
WHERE 
	1 = 1 
GROUP BY
	tb.REGION_ID,
	tb.TYPE_MEMBER,
	e.REGION_NAME
ORDER BY
	tb.REGION_ID,
	tb.TYPE_MEMBER ASC

--แบบที่ 8

SELECT
	SUM (tb.COUNTS) AS total_member,
	tb.CHAPA_CLOUD_ID,
	tb.DEP_NAME,
	c.OFFICE_NAME,
	d.BRANCH_NAME,
	e.REGION_NAME,
	tb.TYPE_MEMBER,
	tb.DEP_TYPE
FROM
	(
		SELECT
			b.OFFICE_ID,
			b.BR_ID,
			b.REGION_ID,
			b.DEP_TYPE,
			COUNTS,
			a.CHAPA_CLOUD_ID,
			b.DEP_NAME,
			a.TYPE_MEMBER
		FROM
			M_AGE_RANGE a
		LEFT JOIN M_BASIC b ON a.CHAPA_CLOUD_ID = b.CHAPA_CLOUD_ID
	) AS tb
LEFT JOIN M_OFFICE c ON tb.OFFICE_ID = c.OFFICE_ID
LEFT JOIN M_BRANCH d ON tb.BR_ID = d.BRANCH_ID
LEFT JOIN M_REGION e ON tb.REGION_ID = e.REGION_ID
WHERE
	1 = 1
GROUP BY
	tb.CHAPA_CLOUD_ID,
	tb.TYPE_MEMBER,
	tb.DEP_TYPE,
	tb.DEP_NAME,
	c.OFFICE_NAME,
	d.BRANCH_NAME,
	e.REGION_NAME
ORDER BY
	tb.CHAPA_CLOUD_ID,
	tb.TYPE_MEMBER ASC
