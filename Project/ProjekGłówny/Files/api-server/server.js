const express = require('express');
const cors = require('cors');
const app = express();
const port = 5000;

app.use(cors());

const photos = [
  { "url": "https://image.ceneostatic.pl/data/article_picture/b8/c8/7532-c068-4964-906e-00c2d6d631a1_medium.jpg", "title": "Sekiro Shadow" },
  { "url": "https://www.cnet.com/a/img/resize/43bf7152f39f90a03df23c97a8a7ebb9a09ea520/hub/2022/02/23/f12a8db7-d99b-4b8d-9b09-d84f12661cf7/elden-ring-plakat.jpg?auto=webp&fit=bounds&height=1200&precrop=571,571,x357,y149&width=1200", "title": "Elden Ring" },
  { "url": "https://assetsio.gnwcdn.com/42614e64c732542509b05d6859cd7bb0ca62f214.jpeg?width=1200&height=1200&fit=bounds&quality=70&format=jpg&auto=webp", "title": "GoW" },
  { "url": "https://cdn-s-thewitcher.cdprojektred.com/witcher3/tiles/community-witcher-screenshot-contest.jpg", "title": "Witcher 3 " },
  { "url": "https://strategus.pl/wp-content/uploads/2023/08/Wymagania-Baldurs-Gate-3.webp", "title": "Baldurs Gate" }
];

app.get('/api/photos', (req, res) => {
  res.json(photos);
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
