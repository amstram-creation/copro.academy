DROP VIEW IF EXISTS taxonomy_with_parent;
CREATE VIEW taxonomy_with_parent AS
  SELECT
    c.id        AS id,
    c.slug,
    c.label,
    c.sort_order,
    c.enabled_at,

    c.parent_id AS parent_id,
    p.slug      AS parent_slug,
    p.label     AS parent_label

  FROM taxonomy AS c
  JOIN taxonomy AS p ON c.parent_id = p.id
WHERE c.revoked_at IS NULL;

DROP VIEW IF EXISTS article_plus;
CREATE VIEW article_plus AS
  SELECT
    a.*,
    c.slug      AS category_slug,
    c.label     AS category_label
  FROM article AS a
  JOIN taxonomy_with_parent AS c ON a.category_id = c.id
  WHERE a.revoked_at IS NULL;

DROP VIEW IF EXISTS training_plus;
CREATE VIEW training_plus AS
  SELECT
    training.*,
    l.slug      AS level_slug,
    l.label     AS level_label,
    trainer.slug      AS trainer_slug,
    trainer.label     AS trainer_label
  FROM training
  JOIN taxonomy_with_parent AS l ON training.level_id = l.id
  LEFT JOIN trainer ON training.trainer_id = trainer.id
  WHERE training.revoked_at IS NULL;

DROP VIEW IF EXISTS contact_request_plus;
CREATE VIEW contact_request_plus AS
  SELECT cr.id, cr.label, cr.content, cr.email, cr.subject_id, cr.status_id, cr.created_at, ts.label as subject_label, tst.label as status_label
  FROM contact_request cr
  LEFT JOIN taxonomy ts ON cr.subject_id = ts.id
  LEFT JOIN taxonomy tst ON cr.status_id = tst.id
  WHERE cr.revoked_at IS NULL
  ORDER BY cr.created_at DESC;

DROP VIEW IF EXISTS event_plus;
CREATE VIEW event_plus AS
  SELECT e.id, e.slug, e.label, e.content, e.category_id, e.event_date, e.duration_minutes, e.price_ht, e.places_max, e.avatar, e.speaker, e.location, e.online, e.enabled_at,
  tc.label as category_label,
  COUNT(eb.event_id) as bookings_count
  FROM event e
  LEFT JOIN taxonomy tc ON e.category_id = tc.id
  LEFT JOIN booking eb ON e.id = eb.event_id AND eb.revoked_at IS NULL
  WHERE e.revoked_at IS NULL
  GROUP BY e.id, e.label, e.category_id, e.event_date, e.places_max, tc.label
  ORDER BY e.event_date ASC


