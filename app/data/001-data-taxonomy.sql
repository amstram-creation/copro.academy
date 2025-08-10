-- =================================================
-- 1) contact_request.status         >      taxonomy
-- 2) booking.status                 >      taxonomy
-- 4) training.level                 >      taxonomy
-- 5) contact_request.subject        >      taxonomy
-- 6) article.category               >      taxonomy
-- 7) event.category                 >      taxonomy
-- =================================================
START TRANSACTION;

-- 1) contact_request.status ⇒ taxonomy
SET @p = 'contact_demande-statut';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Statut de la demande', 1);
SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('statut-en-attente', @p, 'En attente', 1),
  ('statut-en-cours',   @p, 'En cours',   2),
  ('statut-resolu',     @p, 'Résolu',     3),
  ('statut-ferme',      @p, 'Fermé',      4);

-- 2) booking.status ⇒ taxonomy
SET @p = 'reservation-statut';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Statut de la réservation', 1);

SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('statut-en-attente', @p, 'En attente', 1),
  ('statut-confirme',   @p, 'Confirmé',   2),
  ('statut-annule',     @p, 'Annulé',     3),
  ('statut-termine',    @p, 'Terminé',    4);

-- 4) training.level ⇒ taxonomy
SET @p = 'formation-niveau';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Niveau de formation', 1);

SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('niveau-debutant',      @p, 'Débutant',      1),
  ('niveau-intermediaire', @p, 'Intermédiaire', 2),
  ('niveau-avance',        @p, 'Avancé',        3),
  ('niveau-expert',        @p, 'Expert',        4);

-- 5) contact_request.subject ⇒ taxonomy
SET @p = 'contact_demande-sujet';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Sujet de la demande', 2);

SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('sujet-general',     @p, 'Général',     1),
  ('sujet-formation',   @p, 'Inscription à une formation',   2),
  ('sujet-support',     @p, 'Support',     3),
  ('sujet-partenariat', @p, 'Partenariat', 4),
  ('sujet-facturation', @p, 'Facturation', 5),
  ('sujet-technique',   @p, 'Technique',   6),
  ('sujet-event',       @p, 'Inscription à un événement',   7),


-- 6) article.category ⇒ taxonomy
SET @p = 'article-categorie';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Catégorie d\'article', 3);

SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('categorie-investissement',   @p, 'Investissement',   1),
  ('categorie-marche',           @p, 'Marché',           2),
  ('categorie-financement',      @p, 'Financement',      3),
  ('categorie-juridique',        @p, 'Juridique',        4),
  ('categorie-fiscalite',        @p, 'Fiscalité',        5),
  ('categorie-construction',     @p, 'Construction',     6),
  ('categorie-renovation',       @p, 'Rénovation',       7),
  ('categorie-gestion_locative', @p, 'Gestion locative', 8);

-- 7) event.category ⇒ taxonomy
SET @p = 'evenement-categorie';
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES (@p, NULL, 'Catégorie d\'événement', 4);

SET @p = (SELECT id FROM taxonomy WHERE slug COLLATE utf8mb4_general_ci = @p);
INSERT INTO taxonomy (slug, parent_id, label, sort_order)
VALUES
  ('categorie-webinaire',        @p, 'Webinaire',        1),
  ('categorie-atelier_pratique', @p, 'Atelier pratique', 2),
  ('categorie-conference',       @p, 'Conférence',       3),
  ('categorie-masterclass',      @p, 'Masterclass',      4),
  ('categorie-visite_guidee',    @p, 'Visite guidée',    5),
  ('categorie-networking',       @p, 'Networking',       6);

COMMIT;
