# iBank

<img src="https://github.com/JanisTensons/i-bank/assets/124044988/c0d24e6c-7091-414b-a261-0ff93ed758d9" alt="iBank-3" width="100px" height="100px">

iBank is a modern online banking platform that allows users to manage their accounts, perform transactions, and access various financial services conveniently.

![ezgif com-crop](https://github.com/JanisTensons/i-bank/assets/124044988/9b63e220-685f-45db-a395-3ab2159b6787)

## Features

- **User Registration and Login**: Users can create accounts and log in securely.
- **Account Management**: View account balances, transaction history, and perform various account-related actions.
- **Fund Transfers**: Transfer money between user accounts securely.
- **Cryptocurrency Investments**: Invest in cryptocurrencies right from your iBank account.

    - **Cryptocurrency Selection**: Choose from a variety of cryptocurrencies for your investments.

    - **Real-time Market Data**: Stay up-to-date with real-time prices and historical data.

    - **Investment Portfolios**: Create and manage your cryptocurrency investment portfolios.

    - **Buy and Sell**: Buy and sell cryptocurrencies securely.

    - **Portfolio Tracking**: Monitor the performance of your cryptocurrency investments.

    - **Security**: We prioritize the security of your cryptocurrency transactions and holdings.

- **Two-Factor Authentication (2FA) by Google**: iBank enhances security by implementing Two-Factor Authentication (2FA) using Google Authenticator. Users are required to provide a secondary verification method, such as a one-time code sent to their mobile device, in addition to their password, to access their accounts and make transfers.


## Getting Started

### Installation

Follow these steps to install and set up iBank:

1. Clone the iBank repository to your local machine:

   ```bash
   git clone https://github.com/JanisTensons/i-bank.git

2. Navigate to the project directory
3. Create a .env file in the root directory and configure your application settings. You can use the provided .env.example file as a template:

    ```bash
    cp .env.example .env

Edit the .env file to set your environment variables.

4. Install the required dependencies:

    ```bash
    composer install

5. Generate a unique application key for your Laravel project:

    ```bash
    php artisan key:generate

6. Set up your database and run migrations:

    ```bash
    php artisan migrate

7. To populate the database with initial data, run the following command:

    ```bash
    php artisan db:seed --class=CurrencyRatesSeeder

8. Start the iBank application:

    ```bash
    php artisan serve

9. The application will be available at http://localhost:8000.
   Open your web browser and navigate to http://localhost:8000 to access the iBank application.

10. To start the queue worker for background job processing, run:

    ```bash
    php artisan queue:work

This command starts a worker process that listens for and processes jobs from the queue.

11. To enable scheduled task execution, set up a cron job to run Laravel's task scheduler. Edit your server's crontab file with:

    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1

12. Run:

    ```bash
    php artisan schedule:run
    
That's it! You've successfully installed iBank on your local machine. You can now start using it for your banking needs.

Feel free to contact us if you have any questions or feedback.

