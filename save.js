/**
 * NOTE: Requires connection to localhost as server.
 * Pure‑Node server (no external packages)
 *   • GET  /           → serves w2.html
 *   • POST /submit     → appends JSON payload as <tr> in workbook.xls
 * The workbook is a basic HTML table saved with .xls; Excel opens it fine.
 */

const http = require('http');
const fs   = require('fs');
const path = require('path');
const PORT = 3000;                      // change if you like

const htmlPath  = path.join(__dirname, 'w2.html');
const bookPath  = path.join(__dirname, 'workbook.xls');

// ───────── helper: ensure workbook exists ─────────
function createSkeleton(headers) {
  const headRow = headers.map(h => `<th>${h}</th>`).join('');
  const skeleton =
`<html><head><meta charset="UTF-8"></head><body>
<table border="1"><thead><tr>${headRow}</tr></thead><tbody>
</tbody></table></body></html>`;
  fs.writeFileSync(bookPath, skeleton, 'utf8');
}

// ───────── helper: append one row ─────────
function appendRow(values) {
  if (!fs.existsSync(bookPath)) createSkeleton(Object.keys(values));
  const file = fs.readFileSync(bookPath, 'utf8');
  const row  = '<tr>' + Object.values(values)
                         .map(v=>`<td>${String(v).replace(/</g,'&lt;')}</td>`)
                         .join('') + '</tr>\n';

  const updated = file.replace('</tbody>', row + '</tbody>');
  fs.writeFileSync(bookPath, updated, 'utf8');
}

// ───────── HTTP server ─────────
const server = http.createServer((req, res) => {
  if (req.method === 'GET') {
    // serve the form
    if (req.url === '/' || req.url === '/w2.html') {
      fs.createReadStream(htmlPath).pipe(res);
    } else {
      res.writeHead(404); res.end('Not found');
    }
  }

  else if (req.method === 'POST' && req.url === '/submit') {
    // collect JSON body
    let body = '';
    req.on('data', chunk => body += chunk);
    req.on('end', () => {
      try {
        const data = JSON.parse(body);
        data.timestamp = new Date().toISOString();  // add a timestamp
        appendRow(data);
        res.end('Thank you! Your W‑2 data has been saved.');
      } catch (err) {
        console.error(err);
        res.writeHead(400);
        res.end('Invalid data.');
      }
    });
  }

  else {
    res.writeHead(405); res.end('Method not allowed');
  }
});

server.listen(PORT, () =>
  console.log(`Server running →  http://localhost:${PORT}/  (Ctrl‑C to stop)`)
);
