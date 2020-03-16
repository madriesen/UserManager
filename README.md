# User management system
API based management system. Focussing on registration and login.

### Registration
A user can create a request to be member of the organisation. The administrator can approve or refuse this request.
If the administrator approves the request, the user gets an invitation.
The administrator can also mannualy send an invitation to a user by inserting an emailaddress.

## Design pattern
The notifications and invitiations are based on an event driven design. 

## Framework
The whole application is made with the Laravel 7.0 Framework. 