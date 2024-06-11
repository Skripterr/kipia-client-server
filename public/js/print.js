Printer = {
    createWindow: function (dataHtml) {
        var createWindow = window.open('', 'Print');
        createWindow.document.write(dataHtml);

        createWindow.print();
        window.onafterprint = createWindow.close();

        return true;
    },

    generateUserReport: function (branch, users) {
        const html = `
          <!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Персонал филиала</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      margin: 0;
                      padding: 0;
                  }
      
                  .container {
                      max-width: 800px;
                      margin: 0 auto;
                      padding: 20px;
                  }
      
                  h1 {
                      text-align: center;
                      color: #333;
                      margin-bottom: 30px;
                  }
      
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-bottom: 30px;
                  }
      
                  th,
                  td {
                      padding: 10px;
                      border: 1px solid #ccc;
                      text-align: left;
                  }
      
                  th {
                      background-color: #f2f2f2;
                      font-weight: bold;
                  }
      
                  tr:nth-child(even) {
                      background-color: #f2f2f2;
                  }
      
                  .footer {
                      text-align: center;
                      color: #999;
                      font-size: 12px;
                      margin-top: 30px;
                      border-top: 1px solid #ccc;
                      padding-top: 10px;
                  }
              </style>
          </head>
          <body>
              <div class="container">
                  <h1>Персонал филиала №${branch.id}</h1>
                  <p>Адрес филиала: ${branch.address}</p>
                  <p>Статус филиала: ${branch.status}</p>
      
                  <table>
                      <thead>
                          <tr>
                              <th>№</th>
                              <th>Имя</th>
                              <th>Отчество</th>
                              <th>Фамилия</th>
                              <th>Должность</th>
                          </tr>
                      </thead>
                      <tbody>
                          ${users.map((user) => {
            return `
                              <tr>
                                  <td>${user.id}</td>
                                  <td>${user.first_name}</td>
                                  <td>${user.middle_name}</td>
                                  <td>${user.last_name}</td>
                                  <td>${user.role}</td>
                              </tr>
                          `;
        }).join('')}
                      </tbody>
                  </table>
      
                  <!--<div class="footer">
                      
                  </div>-->
              </div>
          </body>
          </html>
        `;

        return html;
    },

    generateBakingReport: function (branch, items) {
        const html = `
          <!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Изделия филиала</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      margin: 0;
                      padding: 0;
                  }
      
                  .container {
                      max-width: 800px;
                      margin: 0 auto;
                      padding: 20px;
                  }
      
                  h1 {
                      text-align: center;
                      color: #333;
                      margin-bottom: 30px;
                  }
      
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-bottom: 30px;
                  }
      
                  th,
                  td {
                      padding: 10px;
                      border: 1px solid #ccc;
                      text-align: left;
                  }
      
                  th {
                      background-color: #f2f2f2;
                      font-weight: bold;
                  }
      
                  tr:nth-child(even) {
                      background-color: #f2f2f2;
                  }
      
                  .footer {
                      text-align: center;
                      color: #999;
                      font-size: 12px;
                      margin-top: 30px;
                      border-top: 1px solid #ccc;
                      padding-top: 10px;
                  }
              </style>
          </head>
          <body>
              <div class="container">
                  <h1>Изделия филиала №${branch.id}</h1>
                  <p>Адрес филиала: ${branch.address}</p>
                  <p>Статус филиала: ${branch.status}</p>
      
                  <table>
                      <thead>
                          <tr>
                              <th>№</th>
                              <th>Название</th>
                              <th>Вес, грамм</th>
                              <th>Стоимость, ₽</th>
                          </tr>
                      </thead>
                      <tbody>
                          ${items.map((item) => {
            return `
                              <tr>
                                  <td>${item.id}</td>
                                  <td>${item.name}</td>
                                  <td>${item.weight}</td>
                                  <td>${item.pricing}</td>
                              </tr>
                          `;
        }).join('')}
                      </tbody>
                  </table>
      
                  <!--<div class="footer">
                      
                  </div>-->
              </div>
          </body>
          </html>
        `;

        return html;
    },

    generateIngredientsReport: function (baking, ingredients) {
        const html = `
          <!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Ингредиенты изделия</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      margin: 0;
                      padding: 0;
                  }
      
                  .container {
                      max-width: 800px;
                      margin: 0 auto;
                      padding: 20px;
                  }
      
                  h1 {
                      text-align: center;
                      color: #333;
                      margin-bottom: 30px;
                  }
      
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-bottom: 30px;
                  }
      
                  th,
                  td {
                      padding: 10px;
                      border: 1px solid #ccc;
                      text-align: left;
                  }
      
                  th {
                      background-color: #f2f2f2;
                      font-weight: bold;
                  }
      
                  tr:nth-child(even) {
                      background-color: #f2f2f2;
                  }
      
                  .footer {
                      text-align: center;
                      color: #999;
                      font-size: 12px;
                      margin-top: 30px;
                      border-top: 1px solid #ccc;
                      padding-top: 10px;
                  }
              </style>
          </head>
          <body>
              <div class="container">
                  <h1>Изделия №${baking.id} «${baking.name}»</h1>
                  <p>Вес изделия: ${baking.weight} грамм</p>
                  <p>Стоимость изделия: ${baking.pricing} ₽</p>
      
                  <table>
                      <thead>
                          <tr>
                              <th>№</th>
                              <th>Название</th>
                              <th>Вес, грамм</th>
                          </tr>
                      </thead>
                      <tbody>
                          ${ingredients.map((ingredient) => {
            return `
                              <tr>
                                  <td>${ingredient.id}</td>
                                  <td>${ingredient.name}</td>
                                  <td>${ingredient.weight}</td>
                              </tr>
                          `;
        }).join('')}
                      </tbody>
                  </table>
      
                  <!--<div class="footer">
                      
                  </div>-->
              </div>
          </body>
          </html>
        `;

        return html;
    },

    generateEquipmentReport: function (type, equipment) {
        const html = `
          <!DOCTYPE html>
          <html lang="en">
          <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Список оборудования</title>
              <style>
                  body {
                      font-family: Arial, sans-serif;
                      margin: 0;
                      padding: 0;
                  }
      
                  .container {
                      max-width: 800px;
                      margin: 0 auto;
                      padding: 20px;
                  }
      
                  h1 {
                      text-align: center;
                      color: #333;
                      margin-bottom: 30px;
                  }
      
                  table {
                      width: 100%;
                      border-collapse: collapse;
                      margin-bottom: 30px;
                  }
      
                  th,
                  td {
                      padding: 10px;
                      border: 1px solid #ccc;
                      text-align: left;
                  }
      
                  th {
                      background-color: #f2f2f2;
                      font-weight: bold;
                  }
      
                  tr:nth-child(even) {
                      background-color: #f2f2f2;
                  }
      
                  .footer {
                      text-align: center;
                      color: #999;
                      font-size: 12px;
                      margin-top: 30px;
                      border-top: 1px solid #ccc;
                      padding-top: 10px;
                  }
              </style>
          </head>
          <body>
              <div class="container">
                  <h1>Тип оборудования: ${type}</h1>
      
                  <table>
                      <thead>
                          <tr>
                              <th>№</th>
                              <th>Название</th>
                              <th>Интервал сан. обработки</th>
                              <th>Последняя сан. обработка</th>
                              <th>Проведена</th>
                          </tr>
                      </thead>
                      <tbody>
                          ${equipment.map((equip) => {
            return `
                              <tr>
                                  <td>${equip.id}</td>
                                  <td>${equip.name}</td>
                                  <td>${Application.g_EquipmentTimeouts[equip.sanitizing_interval]}</td>
                                  <td>${equip.last_sanitizing_date}</td>
                                  <td></td>
                              </tr>
                          `;
        }).join('')}
                      </tbody>
                  </table>
      
                  <!--<div class="footer">
                      
                  </div>-->
              </div>
          </body>
          </html>
        `;

        return html;
    },

}