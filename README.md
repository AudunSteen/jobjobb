# Jobbsøkesystem Prosjekt av Audun og Tore- README

Dette jobbsøkesystemprosjektet er utviklet med PHP og bruker en phpmyadmin-database kalt IS115DB for å lagre informasjon om jobbannonser, søknader, og brukere. Nedenfor finner du en oversikt over databasestrukturen

## Database Oversikt:

### Database Navn: IS115DB

#### `jobbannonser` Tabell:

Inneholder informasjon om jobbannonser.
Felter:

- id: Unik identifikator.
- tittel: Tittel på jobbannonsen.
- beskrivelse: Beskrivelse av jobben.
- publiseringsdato: Dato annonse ble publisert.
- interesse: Interessekategori for jobben.
- søknadsfrist: Søknadsfrist for jobben.
- arbeidsgiver_id: Unik identifikator knyttet til `users`-tabellen.

#### `soknader` Tabell:

Registrerer jobbsøknader.
Felter:

- soknad_id: Unik identifikator.
- jobbannonse_id: Koblet til `jobbannonser`-tabellen.
- jobbsoker_id: Koblet til `users`-tabellen (jobbsøker).
- soknadstekst: Tekst av søknaden.
- soknadsdato: Dato for søknad.
- pdf_path: Filbane til søknadens PDF.

#### `users` Tabell:

Lagrer informasjon om brukere (jobbsøkere og arbeidsgivere).
Felter:

- id: Unik identifikator.
- username: Brukernavn.
- password: Passord.
- phoneNumber: Telefonnummer.
- email: E-postadresse.
- userType: Type bruker (jobbsøker eller arbeidsgiver).
- profilePicture: Filbane til profilbilde.
- information: Ytterligere informasjon om brukeren.
- cvFile: Filbane til CV.

## Testdata fra Database ligger i filen "is115DB_1.sql, rett over README-filen
