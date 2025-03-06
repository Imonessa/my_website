function setStyle(category) {
    document.body.className = category;

    if (category === 'student') {
        document.getElementById('header-title').textContent = 'Добро пожаловать в мир кибербезопасности!';
        document.getElementById('header-text').textContent = 'Учись защищать себя и стань кибер-ниндзя!';
        document.getElementById('news-content').innerHTML = `
            <p><strong>Взломали стримера — спаси свой аккаунт!</strong><br>Хакеры угадали пароль и увели Twitch-аккаунт. Узнай, как сделать свой нерушимым.</p>
            <p><strong>Опасные ссылки в Discord</strong><br>Кто-то прислал странный линк? Это может быть фишинг — разберись, как не попасться.</p>
        `;
        document.getElementById('career-content').innerHTML = `
            <p><strong>Этичный хакер</strong><br>Взламывай сайты легально и защищай интернет. Стань кибер-ниндзя!</p>
            <p><strong>Тестировщик безопасности</strong><br>Ищи баги в играх и приложениях, чтобы их не нашли хакеры.</p>
        `;
        document.getElementById('library-content').innerHTML = `
            <p><strong>5 правил безопасного интернета</strong><br>Быстро и стильно — защити себя в сети!</p>
            <p><strong>Как придумать крутой пароль</strong><br>Простые шаги, чтобы твой аккаунт не угнали.</p>
        `;
    } else if (category === 'college') {
        document.getElementById('header-title').textContent = 'Cybersecurity Hub';
        document.getElementById('header-text').textContent = 'Овладей навыками защиты в цифровом хаосе';
        document.getElementById('news-content').innerHTML = `
            <p><strong>Вирус 2025: новая угроза</strong><br>Шифрует файлы и требует выкуп. Разберись, как его остановить.</p>
            <p><strong>Утечка данных в университете</strong><br>Хакеры украли оценки студентов. Узнай, как защищают сети.</p>
        `;
        document.getElementById('career-content').innerHTML = `
            <p><strong>Аналитик безопасности</strong><br>Ищи слабости и спасай компании от хакеров.</p>
            <p><strong>Сетевой инженер</strong><br>Настраивай сети так, чтобы их не пробили.</p>
        `;
        document.getElementById('library-content').innerHTML = `
            <p><strong>План карьеры в кибербезопасности</strong><br>От новичка до работы — твой путь начинается тут.</p>
            <p><strong>Основы шифрования</strong><br>Узнай, как работают коды в интернете.</p>
        `;
    } else if (category === 'pro') {
        document.getElementById('header-title').textContent = 'Cybersecurity Hub';
        document.getElementById('header-text').textContent = 'Стань мастером в мире киберугроз';
        document.getElementById('news-content').innerHTML = `
            <p><strong>Баг в OpenSSL: обновляйся!</strong><br>Уязвимость в 3.2.1. Патч вышел — проверь свои сервера.</p>
            <p><strong>Атака на облако</strong><br>Новая схема обхода MFA. Как защитить данные?</p>
        `;
        document.getElementById('career-content').innerHTML = `
            <p><strong>Криптограф</strong><br>Кодируй так, чтобы никто не разгадал.</p>
            <p><strong>Pentester</strong><br>Взламывай системы, чтобы их улучшить.</p>
        `;
        document.getElementById('library-content').innerHTML = `
            <p><strong>ТОП-5 инструментов анализа</strong><br>Wireshark и другие — как их настроить.</p>
            <p><strong>Гайд по Kali Linux</strong><br>Освой систему для профи за 1 день.</p>
        `;
    }
}