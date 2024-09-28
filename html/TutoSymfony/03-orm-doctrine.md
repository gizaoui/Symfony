# L'ORM Doctrine

## Environnement


Configurer la connexion à la base de données PostgreSQL via le fichier *TutoSymfony/.env* en indiquant la version (`psql -V`).

```bash
DATABASE_URL="postgresql://$POSTGRES_USER:$POSTGRES_PASSWORD@$POSTGRES_URL:5432/$POSTGRES_DB?serverVersion=15.8&charset=utf8"
```

L'url de connexion est construite via les variables d'environnements consignées dans le fichier *.bashrc*

```bash
# PGPASSWORD=postgres psql -h localhost -U postgres -d mydb
export POSTGRES_USER="postgres"
export POSTGRES_PASSWORD="postgres"
export POSTGRES_DB="mydb"
export POSTGRES_URL="localhost"
```




