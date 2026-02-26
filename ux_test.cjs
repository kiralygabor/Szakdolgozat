const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

async function runTest() {
  // Set HOME manually for the process
  process.env.HOME = process.env.USERPROFILE || 'C:\\Users\\ngerg';

  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext();
  const page = await context.newPage();

  const pages = [
    { name: 'Home', url: 'http://localhost:8081/index' },
    { name: 'HowItWorks', url: 'http://localhost:8081/howitworks' },
    { name: 'Category', url: 'http://localhost:8081/category' },
    { name: 'Tasks', url: 'http://localhost:8081/tasks' },
    { name: 'Login', url: 'http://localhost:8081/login' },
    { name: 'Register', url: 'http://localhost:8081/registration' }
  ];

  const screenshotsDir = path.join(__dirname, 'ux_screenshots');
  if (!fs.existsSync(screenshotsDir)) {
    fs.mkdirSync(screenshotsDir);
  }

  const results = [];

  for (const p of pages) {
    console.log(`Testing ${p.name}...`);
    try {
      await page.goto(p.url, { waitUntil: 'domcontentloaded', timeout: 60000 });
      // Wait a bit more for some dynamic content
      await page.waitForTimeout(2000);
      const screenshotPath = path.join(screenshotsDir, `${p.name.toLowerCase()}.png`);
      await page.screenshot({ path: screenshotPath, fullPage: true });
      results.push({ page: p.name, status: 'Success', path: screenshotPath });
    } catch (error) {
      console.error(`Failed to test ${p.name}:`, error.message);
      results.push({ page: p.name, status: 'Failed', error: error.message });
    }
  }

  await browser.close();
  console.log('UX Test Results:', JSON.stringify(results, null, 2));
}

runTest().catch(console.error);
