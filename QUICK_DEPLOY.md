# Quick Deploy to Render - Live Demo Setup

Follow these steps to get your live demo online:

## Step 1: Create Render Account
1. Go to https://render.com
2. Click "Sign Up" 
3. Sign up with GitHub (easiest option)
4. Verify your email if needed

## Step 2: Connect Your Repository
1. After logging in, click "New +" button
2. Select "Web Service"
3. Click "Connect GitHub" (if not already connected)
4. Authorize Render to access your GitHub account
5. Find and select: `IN-OFF-CAMPUS-ACTIVITY-SCHEDULING-INFORMATION-SYSTEM`
6. Click "Connect"

## Step 3: Configure Deployment
Render will automatically detect your `render.yaml` file. Configure:

**Basic Settings:**
- Name: `spup-activity-system` (or keep default)
- Region: Choose closest to your location
- Branch: `main`

**Render will automatically use the settings from render.yaml:**
- PHP environment
- PostgreSQL database
- Auto-build and deployment

## Step 4: Deploy
1. Click "Create Web Service"
2. Wait for deployment (5-10 minutes)
3. You'll see a live URL like: `https://spup-activity-system.onrender.com`

## Step 5: Access Your Live Demo
Once deployment is complete:
1. Click on your service name
2. Click the URL shown at the top
3. Your app will be live!

## Step 6: Test with Demo Accounts
Use the credentials from `DEMO_ACCOUNTS.md`:
- Admin: `admin@spup.edu.ph` / `admin123`
- Student: `student3@spup.edu.ph` / `student123`
- Adviser: `adviser1@spup.edu.ph` / `adviser123`

## Important Notes:
- First deployment takes 5-10 minutes
- The free tier has some limitations but works for demos
- Database will be automatically seeded with demo accounts
- Your app will sleep after 15 minutes of inactivity (free tier)
- It takes ~30 seconds to wake up when accessed

## Troubleshooting:
If deployment fails:
1. Check the deployment logs in Render dashboard
2. Make sure all files are pushed to GitHub
3. Verify the render.yaml file is present

## Alternative: Railway
If Render doesn't work, try Railway:
1. Go to https://railway.app
2. Click "Start New Project"
3. Connect GitHub
4. Select your repository
5. Railway will auto-detect Laravel
6. Deploy with one click

Both services provide free tiers suitable for portfolio demos!
