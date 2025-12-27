# PHP Auth System 
A simple yet secure login and signup system built with PHP and MySQL.

## Features 
- User registration & login
- Password hashing for security
- Session management

## Tech Stack:
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/msh31/php-auth-system.git
   cd php-auth-system
   ```

2. **Set up environment variables**
   - Create a `.env` file in the root directory and add your database credentials:
     ```
     DB_HOST=localhost
     DB_USER=your_username
     DB_PASS=your_password
     DB_NAME=php_auth_db
     ```
   - Install composer packages:
     ```bash
     composer install
     ```

3. **Set up the database**
   - Import the `database.sql` file into your MySQL database.

4. **Run the project**
   - Using Docker:
      ```bash
        # dev
        docker compose --profile dev up -d
        # prod
        docker compose --profile prod up -d
      ```
   - Open `http://localhost:6969` in your browser.

## Contributing
Pull requests are welcome! Feel free to fork this repository and improve upon it.

## License
This project is licensed under the MIT License.

---
Made with ❤️ by [Marco](https://marco007.dev/)

