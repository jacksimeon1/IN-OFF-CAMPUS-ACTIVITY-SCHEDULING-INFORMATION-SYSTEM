# Deployment Guide for SPUP Activity System

## Step 1: Create GitHub Repository

✅ **Already completed!** Repository created at:
https://github.com/jacksimeon1/IN-OFF-CAMPUS-ACTIVITY-SCHEDULING-INFORMATION-SYSTEM

## Step 2: Connect to Your GitHub Account

✅ **Already completed!** Your code is now at:
https://github.com/jacksimeon1/IN-OFF-CAMPUS-ACTIVITY-SCHEDULING-INFORMATION-SYSTEM

## Step 3: Deployment Options

Since this is a Laravel (PHP) application, you cannot use GitHub Pages (which is for static sites). Here are your options:

### Option A: Free PHP Hosting Services

**1. Render (render.com)**
- Free tier available
- Supports Laravel with some configuration
- Automatic SSL
- Easy deployment from GitHub

**2. Railway (railway.app)**
- Free tier ($5 credit)
- Supports PHP/Laravel
- Easy GitHub integration
- Built-in database

**3. Vercel (with PHP runtime)**
- Free tier available
- Requires additional configuration for PHP
- Good for portfolio visibility

**4. 000webhost / InfinityFree**
- Completely free PHP hosting
- Limited features but works for demo
- Manual deployment required

### Option B: Paid Hosting (Recommended for Professional Portfolio)

**1. DigitalOcean**
- $4-6/month
- Full control over server
- Professional appearance
- Can deploy multiple projects

**2. Laravel Forge**
- $12/month
- Official Laravel deployment tool
- Professional setup
- Easy management

**3. Shared Hosting (Hostinger, Bluehost, etc.)**
- $2-5/month
- Easy cPanel setup
- Good for simple demos

### Option C: Local Demo (Easiest for Portfolio)

For your portfolio, you can:
1. Host the code on GitHub (public repository)
2. Create a video walkthrough of the system
3. Add screenshots and demo credentials
4. Link to the GitHub repository

This is often better for portfolios since:
- No hosting costs
- Shows your code quality
- Demonstrates the system via video/screenshots
- Professional appearance

## Step 4: Recommended Portfolio Approach

**Best for your portfolio:**

1. **Push code to GitHub** (follow Step 2)
2. **Create a README.md** with:
   - Project description
   - Screenshots of the system
   - Demo credentials (from DEMO_ACCOUNTS.md)
   - Tech stack used
   - Features list
3. **Record a demo video** showing:
   - Admin login and dashboard
   - Student creating activity
   - Approval workflow
   - Different user roles
4. **Add to your portfolio website**:
   - Link to GitHub repository
   - Embed demo video
   - Add project description
   - Include live demo credentials

## Step 5: If You Want Live Demo

If you really want a live demo accessible online, I recommend **Render**:

1. Create account at render.com
2. Connect your GitHub account
3. Select "New Web Service"
4. Choose your `spup-activity-system` repository
5. Configure:
   - Runtime: PHP
   - Build Command: `composer install --no-interaction --prefer-dist --optimize-autoloader && php artisan key:generate && php artisan migrate --force && php artisan db:seed --force`
   - Start Command: `php artisan serve --host 0.0.0.0 --port $PORT`
6. Add environment variables (from your .env file)
7. Deploy

## Important Notes

- **Never commit .env file** with real credentials
- Use the demo accounts from DEMO_ACCOUNTS.md
- For production, change all default passwords
- This system requires MySQL database
- Some hosting services provide built-in databases

## Quick Commands

```bash
# Push to your GitHub
git remote remove origin
git remote add origin https://github.com/jacksimeon1/spup-activity-system.git
git push -u origin main

# Check current remote
git remote -v

# View commit history
git log --oneline
```
