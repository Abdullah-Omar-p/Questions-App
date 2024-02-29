# [ [Questions Project - API Documentation](#) ]

## Connect with the Developer

#### Feel free to reach out if you have any questions, suggestions, or just want to connect!

- **LinkedIn:** [Abdullah Omar](https://www.linkedin.com/in/abdullah-omar-81196420a?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app)
- **WhatsApp:** [+01144393582](https://wa.me/01144393582)
- **Email:** [abdullahomarj1@gmail.com](abdullahomarj1@gmail.com)
- **Website:** [Eng-AbdullhOmar.online](https://www.eng-abdullahomar.online)
- **Telegram:** [@abdullahomar_p](https://t.me/abdullahomar_p)


# ``Description Summury``
#### this is questions app for 'fatwa' , and contains notification and permissions and activities part and crud operations for all models.


# ``Authentication Endpoints``
### - [Register](#)
**``URL``**: /auth/register <br>
**``Method``**: POST <br>
**``Description``**: Register a new user. <br>
#### Request Body:
**``name``**: name <br>
**``email``**: email <br>
**``password``**: password <br>
**``password_confirmation``**: Confirm Password <br>

#### Response:
Returns a success message if registration is successful. <br>

### - [Login](#)
**``URL``**: /auth/login <br>
**``Method``**: POST <br>
**``Description``**: Authenticate a user and generate an access token. <br>
#### Request Body:
**``email``**: User's email <br>
**``password``**: User's password <br>
#### Response:
Returns an access token if authentication is successful. <br>

### - [Logout](#)
``URL``: /auth/logout <br>
``Method``: POST <br>
``Description``: Logout the currently authenticated user. <br>
``Authorization Header``: Bearer token <br>
#### Response:
Returns a success message upon successful logout. <br>

### - [delete-account](#)
``URL``: /auth/delete-account <br>
``Method``: POST <br>
``Description``: Delete the currently authenticated user. <br>
``Authorization Header``: Bearer token <br>
#### Response:
Your account has been successfully deleted. <br>

### - [check-email](#)
**``URL``**: /auth/check-email <br>
**``Method``**: POST <br>
**``Description``**: Send Email By Code To Check Is Real. <br>
#### Request Body:
**``email``**: User's email <br>
#### Response:
Returns an code token to the email. <br>

### - [password/reset](#)
**``URL``**: /auth/password/reset <br>
**``Method``**: POST <br>
**``Description``**: Send Email By Code To Check Is Real. <br>
#### Request Body:
**``email``**: User's email <br>
**``token``**: Received Token <br>
**``password``**: Confirmed <br>
#### Response:
password reset successfully. <br>

### - [password/email](#)
**``URL``**: /auth/password/email <br>
**``Method``**: POST <br>
**``Description``**: Send Email To Check Is Real. <br>
#### Request Body:
**``email``**: User's email <br>
#### Response:
we sent an email with code of 6 numbers to your email. <br>
