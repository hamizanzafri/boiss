const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

(async () => {
    const htmlPath = path.resolve(__dirname, 'storage', 'receipt.html');
    const outputPath = path.resolve(__dirname, 'storage', 'receipt.png');
    const htmlContent = fs.readFileSync(htmlPath, 'utf8');

    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    await page.setContent(htmlContent, {
        waitUntil: 'networkidle0'
    });

    // Add a delay to ensure the content is rendered
    await new Promise(resolve => setTimeout(resolve, 1000));

    // Capture only the receipt content
    const element = await page.$('.receipt-container');
    if (!element) {
        console.error('Receipt container not found!');
        await browser.close();
        process.exit(1);
    }

    await element.screenshot({ path: outputPath });

    await browser.close();
})();
