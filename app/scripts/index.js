const puppeteer = require('puppeteer');
const fs = require('fs');

let scrape = async () => {
    const browser = await puppeteer.launch({args: ['--no-sandbox']});
    const page = await browser.newPage();

    await page.goto('https://www.amazon.com/Best-Sellers-Computers-Accessories-Desktop/zgbs/pc/565098/');

    const result = await page.evaluate(() => {
        const array = [];

        function randomDate() {
            const startDate = new Date(2019, 10, 1).getTime();
            const endDate =  new Date(2019, 10, 5).getTime();
            const spaces = (endDate - startDate);
            let timestamp = Math.round(Math.random() * spaces);

            timestamp += startDate;

            return JSON.stringify(new Date(timestamp));
        }

        [...document.querySelectorAll(".p13n-sc-truncated")]
            .forEach((elem, index) => {
                array.push({
                    id: index,
                    name: elem.innerText,
                    date: randomDate()
                })
            });

        [...document.querySelectorAll(".p13n-sc-price")]
            .forEach((elem, index) => {
                array[index]['price'] = elem.innerText
            });

        [...document.querySelectorAll(".a-section.a-spacing-small img")]
            .forEach((elem, index) => {
                array[index]['img'] = elem.getAttribute('src')
            });

        return array
    });

    return result;

    await browser.close();
};

scrape().then((res) => {
    fs.writeFile("products.json", JSON.stringify(res), function(err) {

        if(err) {
            return console.log(err);
        }

        console.log("The file was saved!");
    }); 
})


