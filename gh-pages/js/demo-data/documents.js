const normativeDocs = [
    {
        title: 'Постанова Кабінету Міністрів України від 12 травня 2023 року № 488.',
        link: 'https://zakon.rada.gov.ua/laws/show/488-2023-%D0%BF#Text'
    },
    {
        title: 'Статут Комунального підприємства "Бюро технічної інвентаризації" Гусятинської селищної ради',
        link: '../doc/Статут_2020.pdf'
    }
];

const orderSamples = [
    {
        title: "Довідка про інвентаризацію об'єкту нерухомого майна",
        link: '#'
    },
    {
        title: "Довідка про реєстрацію об'єкту нерухомого майна",
        link: '#'
    }
];

const servicePrices = [
    {
        title: 'Вартість послуг', 
        link: '#'
    }
];

function renderDocuments(containerId, docs) {
  const container = document.getElementById(containerId);
  if (!container || !docs?.length) return;

  docs.forEach(doc => {
    const isFakeLink = !doc.link || doc.link === '#';

    const wrapper = document.createElement('div');
    wrapper.dataset.aos = 'fade-up';
    wrapper.dataset.aosDuration = '1000';

    const p = document.createElement('p');
    p.className = 'section_content documents_links';

    const a = document.createElement('a');
    a.className = 'btn_documents';
    a.textContent = `-> ${doc.title}`;
    a.href = isFakeLink ? window.location.pathname : doc.link;

    if (!isFakeLink) {
      a.target = '_blank';
      a.rel = 'noopener';
    }

    p.appendChild(a);
    wrapper.appendChild(p);
    container.appendChild(wrapper);
  });
}

