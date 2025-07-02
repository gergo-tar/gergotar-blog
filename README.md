[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F335fed31-bd4c-40cb-b3ce-28942b1edaed%3Fdate%3D1%26label%3D1&style=flat-square)](https://forge.laravel.com/servers/448934/sites/2101099)

# Laravel Blog Application

The source code of my blog: [https://gergotar.com](https://gergotar.com)  
This project is built using Laravel, Livewire, and Tailwind CSS. It follows Domain-Driven Design principles and is set up for local development using Laravel Sail.

## Table of Contents

-   [Prerequisites](#prerequisites)
-   [Installation](#installation)
-   [Running the Application](#running-the-application)
-   [Project Structure](#project-structure)
-   [Configuration](#configuration)
-   [Used Packages](#used-packages)
-   [Contributing](#contributing)

## Prerequisites

-   Docker
-   Docker Compose

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/gergo-tar/gergotar-blog.git
    cd gergotar-blog
    ```

2. **Install Composer dependencies:**

    ```bash
    composer install
    ```

3. **Copy the example environment file:**

    ```bash
    cp .env.example .env
    ```

4. **Configure your app:**
   Edit the .env file. See the [configuration](#configuration) section for more details.

5. **Generate the application key:**

    ```bash
    php artisan key:generate
    ```

    You can also use Sail for this command after starting your container in local environment:

    ```bash
    sail artisan key:generate
    ```

6. **Create a symbolic link of the storage folder:**

    ```bash
    php artisan storage:link
    ```

7. **Run the migration and seeders:**

    ```bash
    php artisan migrate --seed
    ```

8. **Publish Filament assets:**

    ```bash
    php artisan filament:assets
    ```

9. **Install Node dependencies:**

    ```bash
    yarn install
    ```

## Running the Application

To run the application in a local development environment using Sail:

1. **Start Sail:**

    ```bash
    ./vendor/bin/sail up -d
    ```

    This command will build the Docker containers and start the application.
    For easier execution of Sail commands, set up a shell alias:

    ```bash
    alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'
    ```

    After setting the alias, you can run:

    ```bash
    sail up -d
    ```

2. **Build your assets:**

    ```bash
    sail yarn build
    ```

3. **Publish Filament assets:**

    ```bash
    sail artisan filament:assets
    ```

4. **Access the application:**
   Open your browser and go to [http://localhost:8000](http://localhost:8000.)

5. **Running Artisan commands with Sail:**
   You can run any Artisan command using Sail:

    ```bash
    sail artisan migrate
    ```

## Deployment

When deploying the application, make sure to run these additional commands since the Filament assets are excluded from version control:

1. **Publish Filament assets:**

    ```bash
    php artisan filament:assets
    ```

2. **Clear and cache configuration:**

    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

3. **Generate sitemap:**
    ```bash
    php artisan sitemap:generate
    ```

## Project Structure

The project follows a Domain-Driven Design approach:

-   `app/App`: Contains the application layer, including Livewire components, resources, and routes.
-   `app/Domain`: Contains the domain layer with actions, models, and query builders.
-   `app/Support`: Contains a helpers.php file for global functions.

## Configuration

The application is configured using the .env file. Key configurations include:

-   `FILAMENT_USER_MAIL_DOMAIN`: Domain for Filament admin user emails. A user with the given domain can access the Filament dashboard. More about Filament in their documentation: [Filament PHP](https://filamentphp.com/).

-   `MAXMIND_LICENSE_KEY`: API key for MaxMind GeoIP services. MaxMind helps to determine the geographical location of users based on their IP addresses. Learn more [here](https://github.com/stevebauman/location?tab=readme-ov-file#setting-up-maxmind-with-a-self-hosted-database-optional).

-   `IP2LOCATIONIO_TOKEN`: API token for IP2Location geolocation services. More info [here](https://www.ip2location.io/).

-   `LOCATION_TESTING`: Enables location testing in the app. During testing, the returned IP address is in the USA. Learn more [here](https://github.com/stevebauman/location?tab=readme-ov-file#retrieve-a-clients-location).

-   `MAIL_CONTACT_ADDRESS`: Your contact address where emails from website visitors via the contact form will be sent.

-   `SOCIAL_CODEPEN_LINK`: URL to your CodePen profile.

-   `SOCIAL_GITHUB_LINK`: URL to your GitHub profile.

-   `SOCIAL_LINKEDIN_LINK`: URL to your LinkedIn profile.

-   `UMAMI_WEBSITE_ID`: Umami Analytics website identifier. Learn more [here](https://umami.is).

## Used Packages

This project utilizes several packages to enhance functionality and improve the development experience:

-   **[artesaos/seotools](https://github.com/artesaos/seotools)**: Provides SEO optimization tools for Laravel, helping you manage meta tags, Open Graph, and Twitter cards.

-   **[filament/filament](https://filamentphp.com/)**: A fast and user-friendly admin panel for Laravel that makes building admin interfaces simple.

-   **[awcodes/filament-curator](https://github.com/awcodes/filament-curator)**: A file management package for Filament that allows for easy media management.

-   **[awcodes/filament-tiptap-editor](https://github.com/awcodes/filament-tiptap-editor)**: A rich text editor for Filament based on the Tiptap editor.

-   **[bezhansalleh/filament-language-switch](https://github.com/bezhanSalleh/filament-language-switch)**: Adds a language switcher to Filament’s admin panel for managing translations.

-   **[codezero/laravel-localized-routes](https://github.com/codezero-be/laravel-localized-routes)**: Helps in handling localized routes to support multiple languages.

-   **[livewire/livewire](https://github.com/livewire/livewire)**: A full-stack framework for Laravel that enables reactive components in PHP.

-   **[lorisleiva/laravel-actions](https://github.com/lorisleiva/laravel-actions)**: Encourages a modular approach by placing logic into self-contained classes called Actions.

-   **[spatie/laravel-sitemap](https://github.com/spatie/laravel-sitemap)**: A package to generate dynamic sitemaps for your Laravel application.

-   **[spatie/laravel-sluggable](https://github.com/spatie/laravel-sluggable)**: Provides a simple way to generate slugs for models in Laravel.

-   **[stevebauman/location](https://github.com/stevebauman/location)**: Retrieves the geographical location of your users by their IP address using multiple drivers like MaxMind, IP2Location, etc.

### Dev Dependencies

-   **[enlightn/security-checker](https://github.com/enlightn/security-checker)**: Scans for known security vulnerabilities in your dependencies.

-   **[larastan/larastan](https://github.com/nunomaduro/larastan)**: A static analysis tool for Laravel, built on top of PHPStan, to catch bugs before they happen.

-   **[laravel-lang/common](https://github.com/Laravel-Lang/common)**: Provides language files for translating the Laravel UI and validation errors.

-   **[laravel/pint](https://github.com/laravel/pint)**: A code style fixer for Laravel using the PSR-12 standard.

-   **[pestphp/pest](https://pestphp.com/)**: A testing framework for PHP, designed to be simpler and faster than PHPUnit.

-   **[phpmd/phpmd](https://phpmd.org/)**: A code analysis tool that checks for potential problems in your PHP code.

-   **[phpro/grumphp](https://github.com/phpro/grumphp)**: Ensures code quality by running tasks like Pint, PHPStan, and Pest before allowing commits.
