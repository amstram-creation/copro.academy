
DROP VIEW IF EXISTS articles_events;
CREATE OR REPLACE VIEW articles_events AS
  SELECT
    -- core identification
    id,
    slug,
    label,
    -- shared fields
    category_id,
    category_label,
    enabled_at,

    -- unified date + type
    enabled_at   AS unified_date,
    'article'    AS type,
    -- article fields
    summary       AS content,          -- short teaser
    reading_time,                     -- minutes to read
    featured,                         -- highlight flag

    -- event placeholders (NULL for articles)
    NULL          AS duration_minutes,
    NULL          AS price_ht,
    NULL          AS places_max,
    NULL          AS speaker,
    NULL          AS location,
    NULL          AS online
  FROM article_plus

UNION ALL

  SELECT
    -- core identification
    id,
    slug,
    label,
    -- shared fields
    category_id,
    category_label,
    enabled_at,

    -- unified date + type
    event_date   AS unified_date,
    'event'      AS type,

    -- event placeholders for excerpt/reading_time/featured
    content      AS content,           -- event description
    NULL         AS reading_time,
    NULL         AS featured,

    -- event-specific fields
    duration_minutes,
    price_ht,
    places_max,
    speaker,
    location,
    online
FROM event_plus;