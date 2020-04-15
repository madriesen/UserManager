# User management system
API based user management system. Focusing on registration and login.

### Registration
A user can create a request to be member of the organisation. The administrator can approve or refuse this request.
If the administrator approves the request, the user gets an invitation.
_The administrator can also manually send an invitation to a user by inserting an emailaddress. **(not implemented)**_

## Patterns
The application is build with the repository pattern. All repositories right now are made for Eloquent models.

## Framework
The whole application is made with the Laravel 7.0 Framework.


# Contribution
## style guide
- always use snake case
- on adding models, create a repository with interface and facade.
 