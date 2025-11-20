# cPanel Git Deployment Guide for MRA Website

This guide will help you set up and deploy your MRA website from Git to cPanel.

## 📋 Prerequisites

- cPanel access with Git Version Control enabled
- Git repository already created and connected (GitHub: `https://github.com/Munkhtsolmon2222/mra-web.git`)
- SSH access to cPanel (optional, for advanced setup)

## 🚀 Step-by-Step Deployment Setup

### Step 1: Configure Git Repository in cPanel

1. **Log in to cPanel**
   - Navigate to your cPanel dashboard
   - Find **"Git Version Control"** under the **Files** section

2. **Create or Connect Repository**
   - If you haven't created a repository yet:
     - Click **"Create"**
     - Repository Name: `mra-web` (or your preferred name)
     - Repository Path: `/home/username/repositories/mra-web` (replace `username` with your cPanel username)
     - **DO NOT** check "Clone a Repository" (we'll connect it manually)

3. **Connect to Your GitHub Repository**
   - In cPanel Git Version Control, select your repository
   - Click **"Manage"** or **"Settings"**
   - Add Remote Repository:
     - Remote Name: `origin`
     - Remote URL: `https://github.com/Munkhtsolmon2222/mra-web.git`
   - Click **"Update Remote"**

### Step 2: Configure Deployment Path

1. **In cPanel Git Version Control:**
   - Select your repository (`mra-web`)
   - Click **"Manage"** or **"Deploy"**
   - Set **Deployment Branch**: `main` (or `master` if that's your default)
   - Set **Deployment Path**: `/home/username/public_html/`
     - Replace `username` with your actual cPanel username
   - **Important**: The deployment path should point to your `public_html` directory

### Step 3: Verify .cpanel.yml Configuration

The repository includes a `.cpanel.yml` file that automates deployment. Verify it contains:

```yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/$$USER/public_html/
    - /bin/cp -Rf public_html/* $$DEPLOYPATH
    - /bin/chmod -R 755 $$DEPLOYPATH
    - /bin/find $$DEPLOYPATH -type f -exec chmod 644 {} \;
    - /bin/find $$DEPLOYPATH -type d -exec chmod 755 {} \;
    - if [ -f public_html/.htaccess ]; then /bin/cp -f public_html/.htaccess $$DEPLOYPATH; fi
```

**Note**: The `$$USER` variable will automatically use your cPanel username.

### Step 4: Initial Deployment

1. **Pull from GitHub:**
   - In cPanel Git Version Control
   - Select your repository
   - Click **"Pull or Deploy"**
   - Select branch: `main`
   - Click **"Update from Remote"**

2. **Deploy to Production:**
   - After pulling, click **"Deploy HEAD Commit"**
   - This will execute the `.cpanel.yml` tasks
   - Files will be copied from `public_html/` in your repo to `/home/username/public_html/` on the server

### Step 5: Verify Deployment

1. **Check File Structure:**
   - Use cPanel File Manager
   - Navigate to `public_html/`
   - Verify all files are present:
     - `index.html`
     - `page6.html`
     - `assets/` directory
     - `award2025/` directory
     - etc.

2. **Test Website:**
   - Visit `https://mongolianrestaurants.mn`
   - Check that pages load correctly
   - Verify images and assets are accessible
   - Test navigation links

## 🔄 Ongoing Deployment Workflow

### Making Updates

1. **Make Changes Locally:**
   ```bash
   # Edit files in public_html/
   git add public_html/page6.html
   git commit -m "Update page6.html"
   git push origin main
   ```

2. **Deploy in cPanel:**
   - Go to cPanel → Git Version Control
   - Select `mra-web` repository
   - Click **"Pull or Deploy"**
   - Select branch: `main`
   - Click **"Update from Remote"**
   - Click **"Deploy HEAD Commit"**

### Alternative: Automatic Deployment (Webhook)

For automatic deployment on push, you can set up a webhook:

1. **In GitHub:**
   - Go to repository Settings → Webhooks
   - Add webhook URL: `https://your-domain.com/cpanel-git-webhook.php`
   - Set content type: `application/json`
   - Select events: `Just the push event`

2. **Create Webhook Handler** (if cPanel supports it):
   - This requires additional server-side configuration
   - Consult your hosting provider for webhook support

## 📁 Repository Structure

Your repository should have this structure:

```
mra-web/
├── .cpanel.yml          # Deployment configuration
├── .gitignore          # Files to exclude from Git
├── README.md           # Project documentation
├── CPANEL_DEPLOYMENT_GUIDE.md  # This guide
└── public_html/        # Website files (deployed to server)
    ├── .htaccess       # Apache configuration
    ├── index.html
    ├── page6.html
    ├── assets/
    ├── award2025/
    └── ...
```

## ⚠️ Important Notes

### File Permissions

After deployment, ensure proper permissions:
- **Files**: 644 (readable by web server)
- **Directories**: 755 (executable by web server)
- The `.cpanel.yml` script handles this automatically

### Excluded Files

The `.gitignore` file excludes:
- `www/` (duplicate directory)
- `logs/`, `tmp/`, `mail/` (server-generated files)
- System files (`.DS_Store`, etc.)
- Backup files

### Preserving .htaccess

The `.htaccess` file in `public_html/` is automatically preserved during deployment.

### Branch Strategy

- **`main` branch**: Production-ready code
- Consider creating a `develop` branch for testing before merging to `main`

## 🐛 Troubleshooting

### Issue: Files Not Deploying

**Solution:**
1. Check deployment path in cPanel Git settings
2. Verify `.cpanel.yml` syntax is correct
3. Check cPanel error logs: `cPanel → Metrics → Errors`
4. Ensure you have write permissions to `public_html/`

### Issue: Permission Errors

**Solution:**
1. In cPanel File Manager, check file permissions
2. Manually set: Files = 644, Directories = 755
3. Verify `.cpanel.yml` chmod commands are executing

### Issue: Website Shows Old Content

**Solution:**
1. Clear browser cache
2. Check if deployment actually completed (check timestamps)
3. Verify you deployed the correct branch
4. Check for CDN/caching services

### Issue: Assets Not Loading

**Solution:**
1. Verify `assets/` directory was deployed
2. Check file paths in HTML (should be relative: `assets/...`)
3. Check `.htaccess` is present and correct
4. Verify file permissions on assets

## 📞 Support

If you encounter issues:
1. Check cPanel error logs
2. Review Git deployment logs in cPanel
3. Verify repository connection status
4. Contact hosting provider if server-side issues

## 🔐 Security Best Practices

1. **Never commit sensitive data:**
   - Passwords
   - API keys
   - Database credentials

2. **Use .gitignore:**
   - Exclude configuration files with sensitive data
   - Exclude log files

3. **Review changes before deploying:**
   - Always test locally first
   - Use a staging branch if possible

## 📚 Additional Resources

- [cPanel Git Documentation](https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-set-up-deployment/)
- [Git Basics](https://git-scm.com/book/en/v2/Getting-Started-Git-Basics)
- [cPanel File Manager Guide](https://docs.cpanel.net/cpanel/files/file-manager/)

---

**Last Updated**: November 2025
**Repository**: https://github.com/Munkhtsolmon2222/mra-web.git

