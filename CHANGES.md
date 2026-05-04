# LifeLink – Changes & Additions Log

## Added

### Bug Fixes
- Fixed wrong namespace import for `UserDTO` in `AdminController` (`App\Http\Controllers\UserDTO` → `App\DTOs\UserDTO`)

### Admin Features
- `PUT /api/users/{user}/ban` — directly ban a user by ID
- `PUT /api/users/{user}/toggle-ban` — toggle ban/unban with a single endpoint; sends appropriate email on each action

### Emails
All emails use Laravel Markdown Mailables and send synchronously via SMTP.

| Mailable | Trigger | Recipient |
|---|---|---|
| `WelcomeMail` | User registers | New user |
| `BannedMail` | Admin bans via report review OR toggle-ban | Banned user |
| `UnbannedMail` | Admin unbans via toggle-ban | Unbanned user |
| `NewCommentMail` | Someone comments on a blood request post | Post owner (not sent if owner comments on own post) |

### Files Created
- `app/Mail/WelcomeMail.php`
- `app/Mail/BannedMail.php`
- `app/Mail/UnbannedMail.php`
- `app/Mail/NewCommentMail.php`
- `resources/views/emails/welcome.blade.php`
- `resources/views/emails/banned.blade.php`
- `resources/views/emails/unbanned.blade.php`
- `resources/views/emails/new-comment.blade.php`

---

## Planned / To Do

### Security (Pre-deployment)
- [ ] Admin middleware — protect admin routes so only users with the `admin` role can access them
- [ ] Rate limiting on login, register, and report endpoints

### Config (Pre-deployment)
- [ ] Fix `config/database.php` default from `sqlite` → `mysql`
- [ ] Create `.ebextensions/laravel.config` for AWS Elastic Beanstalk (migrations, cache, document root)
- [ ] Frontend build step for EB deployment

### Features
- [ ] Unread/pending reports list endpoint for admin
- [ ] Close/fulfill a blood request (poster marks it as done)
- [ ] Blood compatibility filtering on the feed based on logged-in user's blood type
- [ ] User profile page with donation count and trust score
