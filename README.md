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

# Endpoints
### Member Request routes:
**prefix:** /api/registration/memberrequest

| Route        | Name               | Arguments                                       |
|--------------|--------------------|-------------------------------------------------|
|`/create`     |memberRequest       |`email_address` => required, `name`, `first_name`|
|`/approve`    |approveMemberRequest|`member_request_id` => required                  |
|`/decline`    |declineMemberRequest|`member_request_id` => required                  |
|`/all`        |getAllMemberRequests|none|


### Invite routes:
**prefix:** /api/registration/invite

| Route        | Name               | Arguments                          |
|--------------|--------------------|------------------------------------|
|`/create`     |Invite              |`member_request_id` => required     |
|`/accept`     |acceptInvite        |`invite_id` => required             |
|`/refuse`     |refuseInvite        |`invite_id` => required             |
|`/all`        |getAllInvites       |none|


### Account routes:
**prefix:** /api/account

| Route        | Name               | Arguments                          |
|--------------|--------------------|------------------------------------|
|`/create`     |Account             |`invite_id` => required             |
|`/all`        |getAllAccounts      |none|

### Profile routes:
**prefix:** /api/profile

| Route        | Name               | Arguments                          |
|--------------|--------------------|------------------------------------|
|`/create`     |Profile             |`account_id` => required, `first_name` => required, `name` => required, `tel`, `birthday`, `profile_picture_url`|
|`/update`     |updateProfile       |`profile_id` => required, `first_name`, `name`,`tel`, `birthday`, `profile_picture_url`|

### Login routes:
**prefix:** /api/authentication

| Route        | Name               | Arguments                          |
|--------------|--------------------|------------------------------------|
|`/login`      |login               |`email_address`=> required, `password`=> required|
|`/checkLogin` |checkLogin          |none|

### Account types routes:
**prefix:** /api/accounttype

| Route        | Name               | Arguments                          |
|--------------|--------------------|------------------------------------|
|`/create`     |accountType         |`title` => required, `description` => required|
|`/update`     |updateAccountType   |`account_type_id` => required, `title`, `description`|

# Contribution
## style guide
- always use snake case
- on adding models, create a repository with interface and facade.
 