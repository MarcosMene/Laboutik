# Laboutik - project description

I want to present my first e-commerce with Symfony 7.
The idea of this project is to create an e-commerce platform for a (fictitious) clothing store.

**The customer**

The customer can see the products and buy them. To do this, the customer needs to create an account with their main details such as name, address, password. Once the customer's data is saved in the database, he can change his information and also ask to recover his password if he doesn't remember it.
The payment system uses Stripe. The customer chooses the address and the carrier. Payment is made by credit card. If payment is accepted, the customer will receive an email confirming their purchase. If the payment is not authorized, the customer receives a message on the website itself to check that the data is indeed correct.

**The store owner**

The owner has access to all the content on the Laboutik site. He, and he alone, has access to the administration page, where he can generate absolutely all the content for the Laboutik site and its customers.
In the administration section, he can create, modify and delete products; product types such as pants, blouses, t-shirts, etc. He has access to all customer orders, and can modify each customer's order: order being prepared, order being sent by carrier. He can also create delivery carriers. And finally, he can modify, via the LabouTik site administration system, the images of the hero that appear on the main page of the LabouTik site, adding as many images as he wishes.

<hr/>

# Laboutik - project process

- Create a e-commerce with Symfony 7
- Form validation for users and data validation
- Database management
- Create an administration interface with EasyAdmin
- Create registration form
- Create login form
- Create a forgotten password system
- Create a user's member space (passwords, adress and order list)
- Create a contact us form
- Change user password
- Show some products on the home page
- Display orders on user's member area and on easyadmin
- Saving form information in database
- Security access to backoffice
- The security of the pages
- Encoding user passwords for security
- Adding, reducing and deleting products in the shopping cart
- Adding, reducing and deleting user's member address
- Creating the order summary
- Email system using Mailjet
- Payment system using Stripe
- Adding delivery information sent to Stripe
- Creation of "Thank you for your order" / "Payment failure" view
- Manage error views to production realease

<hr/>

# Laboutik - development

- Symfony 7.0.2
- PHP 8.2.4
- Bootstrap 5.3
- Doctrine 2.17
- Easyadmin 4.8
- Mailjet 1.6
- Stripe 13.9

# Laboutik - Screenshots

**Here some images of the project**

1. Webpage - client

  <img src="public/assets/screenshots/home.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/nos_produits.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/produit.jpg" width="100" style="margin:20px"/>

  <img src="public/assets/screenshots/mon_panier.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/recapitulatif.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/payment.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/mes_commandes.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/ma_commande.jpg" width="100" style="margin:20px"/>
  <img src="public/assets/screenshots/mon_compte.jpg" width="100" style="margin:20px"/>

<hr/>

1. Administration

<img src="public/assets/screenshots/products.jpg" width="100" style="margin:20px"/>
<img src="public/assets/screenshots/category.jpg" width="100" style="margin:20px"/>
