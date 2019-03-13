### Directory
Add Asset directory(manually) outside src

```
    directory structure
    .
    ├── asset                   # temp uploaded images/documents
        └── Upload
                ├── franchiseApplication
                ├── profileImage
                ├── CIBI
                ├── harrisonAssessment
                ├── GTM
                ├── interviewSummary
                ├── siteApplication
                └── siteAssessmentDocuments
    ├── db                      # for migration
        ├── migrations
        └── seeds
    ├── logs                    # consist of request & response logs
    ├── src                     # Application store
    ├── public                  # point to virtual host
    ├── vendor                  # packages store
    └── README.md

```

# Setup Application

Run sql script 
CREATE SCHEMA development_db;

``` bash
# Install Dependencies
composer install

# Run migration
vendor\bin\phinx migrate -e development

# Run Seed
vendor\bin\phinx seed:run

# Run Application
composer start

# Test Application
composer test

```