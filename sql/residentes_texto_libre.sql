-- Migra centro_medico_e, medico_tratante y parentesco de residentes
-- de referencia numérica (FK) a texto libre.
-- IMPORTANTE: aplicar esto en Railway solo DESPUÉS de que el código nuevo
-- (que ya no hace JOIN contra centro_medico/medico_tratante/parentesco)
-- esté desplegado. Si se aplica antes, el código viejo dejará de funcionar.

SET search_path TO geriatrico;

ALTER TABLE residentes DROP CONSTRAINT IF EXISTS residentes_contro_medico_e_fkey;
ALTER TABLE residentes DROP CONSTRAINT IF EXISTS residentes_medico_tratante_fkey;
ALTER TABLE residentes DROP CONSTRAINT IF EXISTS residentes_parentesco_fkey;

ALTER TABLE residentes ALTER COLUMN centro_medico_e TYPE text USING centro_medico_e::text;
ALTER TABLE residentes ALTER COLUMN medico_tratante TYPE text USING medico_tratante::text;
ALTER TABLE residentes ALTER COLUMN parentesco TYPE text USING parentesco::text;
