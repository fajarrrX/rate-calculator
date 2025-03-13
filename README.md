<h1 align="center">
  DHL Rate Calculator API
  <br>
</h1>

## Usage
* Serve as superadmin access to create country record and upload rate cards
* Serve APIs to DHL Rate Calculator Frontend

## Configure Environment File
```bash
# Change database configuration
DB_DATABASE={YOUR_DATABASE_NAME}
DB_USERNAME={YOUR_DATABASE_USERNAME}
DB_PASSWORD={YOUR_DATABASE_PASSWORD}
```

## How to run

```bash
# Clone this repository
$ git clone https://github.com/Asdor-digital/dhl-rates-calculator-api.git

# Go into the repository
$ cd dhl-rates-calculator-api

# Install dependencies
$ composer install

# Run migration
$ php artisan migrate

# Run seeder
$ php artisan db:seed

# Run in localhost
$ php artisan serve
```