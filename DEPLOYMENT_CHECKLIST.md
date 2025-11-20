# 🚀 Quick Deployment Checklist

Use this checklist when deploying updates to cPanel.

## Pre-Deployment

- [ ] All changes committed to Git
- [ ] Changes pushed to GitHub (`git push origin main`)
- [ ] Tested changes locally (if possible)
- [ ] Reviewed `.gitignore` to ensure no sensitive files are included

## cPanel Deployment Steps

### 1. Connect to cPanel
- [ ] Logged into cPanel dashboard
- [ ] Navigated to **Git Version Control** (under Files section)

### 2. Select Repository
- [ ] Selected repository: `mra-web`
- [ ] Verified remote URL: `https://github.com/Munkhtsolmon2222/mra-web.git`

### 3. Pull Latest Changes
- [ ] Clicked **"Pull or Deploy"**
- [ ] Selected branch: `main`
- [ ] Clicked **"Update from Remote"**
- [ ] Verified pull was successful

### 4. Deploy to Production
- [ ] Clicked **"Deploy HEAD Commit"**
- [ ] Verified deployment completed without errors
- [ ] Checked deployment logs (if available)

### 5. Verify Deployment
- [ ] Website loads: https://mongolianrestaurants.mn
- [ ] Homepage displays correctly
- [ ] Navigation menu works
- [ ] Images/assets load properly
- [ ] Tested key pages:
  - [ ] `index.html` (Homepage)
  - [ ] `page6.html` (MRA Awards 2025)
  - [ ] `members.html` (Membership)
  - [ ] `course.html` (Training)

### 6. Post-Deployment
- [ ] Checked file permissions (should be 644 for files, 755 for directories)
- [ ] Verified `.htaccess` is present and working
- [ ] Cleared browser cache (if seeing old content)
- [ ] Tested on mobile device (responsive design)

## Troubleshooting Quick Reference

| Issue | Solution |
|-------|----------|
| Files not updating | Check deployment path, verify `.cpanel.yml` |
| Permission errors | Manually set permissions in File Manager |
| Old content showing | Clear browser cache, check deployment timestamp |
| Assets not loading | Verify `assets/` directory deployed, check paths |
| 404 errors | Check `.htaccess` file, verify file paths |

## Emergency Rollback

If deployment causes issues:

1. **In cPanel Git Version Control:**
   - Select repository
   - Click **"Pull or Deploy"**
   - Select previous commit
   - Click **"Deploy HEAD Commit"**

2. **Or manually restore:**
   - Use cPanel File Manager
   - Restore from backup (if available)
   - Or manually copy files from previous version

## Contact

- **Repository**: https://github.com/Munkhtsolmon2222/mra-web.git
- **Full Guide**: See [CPANEL_DEPLOYMENT_GUIDE.md](./CPANEL_DEPLOYMENT_GUIDE.md)

---

**Last Updated**: November 2025

