const dataUrl = './data/portfolio.json';

async function loadPortfolioData() {
  try {
    const response = await fetch(dataUrl);
    if (!response.ok) throw new Error('Data could not be loaded');
    return await response.json();
  } catch (error) {
    console.error(error);
    return null;
  }
}

function getText(data, key, lang) {
  return data?.site?.translations?.[lang]?.[key] || '';
}

function renderPortfolio(data) {
  const lang = localStorage.getItem('portfolio-lang') || 'tr';

  const t = data.site.translations[lang];
  document.documentElement.lang = lang;
  document.title = t.site_title;

  document.getElementById('siteLogo').textContent = data.site.title;
  document.getElementById('navAbout').textContent = t.nav_about;
  document.getElementById('navSkills').textContent = t.nav_skills;
  document.getElementById('navProjects').textContent = t.nav_projects;
  document.getElementById('navContact').textContent = t.nav_contact;

  document.getElementById('heroBadge').textContent = t.hero_badge;
  document.getElementById('heroGreeting').textContent = t.hero_greeting;
  document.getElementById('heroHeading').textContent = t.hero_name;
  document.getElementById('heroRole').textContent = t.hero_role;
  document.getElementById('heroDesc').textContent = t.hero_desc;
  document.getElementById('heroCta').textContent = t.hero_cta;
  document.getElementById('heroContact').textContent = t.hero_contact;

  document.getElementById('aboutLabel').textContent = t.about_label;
  document.getElementById('aboutHeading').textContent = t.about_title;
  document.getElementById('aboutText').textContent = t.about_text;
  document.getElementById('statExp').textContent = t.stats_exp;
  document.getElementById('statProjects').textContent = t.stats_projects;
  document.getElementById('statTech').textContent = t.stats_tech;
  document.getElementById('quickInfoTitle').textContent = t.quick_info_title;

  const quickInfoList = document.getElementById('quickInfoList');
  quickInfoList.innerHTML = '';
  t.quick_info.forEach((item) => {
    const li = document.createElement('li');
    li.className = 'about-card-item';
    li.innerHTML = `<span class="about-card-icon" aria-hidden="true">•</span><span>${item}</span>`;
    quickInfoList.appendChild(li);
  });

  document.getElementById('skillsLabel').textContent = t.nav_skills;
  document.getElementById('skillsHeading').textContent = t.skills_title;
  document.getElementById('skillsSubtitle').textContent = t.skills_subtitle;

  const skillsList = document.getElementById('skillsList');
  skillsList.innerHTML = '';
  data.skills.forEach((skill, index) => {
    const div = document.createElement('div');
    div.className = 'skill-item reveal';
    div.style.transitionDelay = `${Math.min(index * 0.07, 0.4)}s`;
    div.innerHTML = `
      <div class="skill-head">
        <span class="skill-name">${skill.name}</span>
        <span class="skill-pct">${skill.level}%</span>
      </div>
      <div class="skill-bar" role="progressbar" aria-valuenow="${skill.level}" aria-valuemin="0" aria-valuemax="100" aria-label="${skill.name} seviyesi">
        <div class="skill-fill" style="width: ${skill.level}%"></div>
      </div>
    `;
    skillsList.appendChild(div);
  });

  document.getElementById('projectsLabel').textContent = t.nav_projects;
  document.getElementById('projectsHeading').textContent = t.projects_title;
  document.getElementById('projectsSubtitle').textContent = t.projects_subtitle;

  const projectsList = document.getElementById('projectsList');
  projectsList.innerHTML = '';
  data.projects.forEach((project, index) => {
    const article = document.createElement('article');
    article.className = 'project-card reveal';
    article.style.transitionDelay = `${Math.min(index * 0.1, 0.4)}s`;
    article.innerHTML = `
      <div class="project-card-accent" aria-hidden="true"></div>
      <div class="project-card-body">
        <div class="project-card-number">${String(index + 1).padStart(2, '0')}</div>
        <h3>${project.title[lang]}</h3>
        <p>${project.desc[lang]}</p>
        <div class="tags" aria-label="Etiketler">
          ${project.tags.map((tag) => `<span class="tag">${tag}</span>`).join('')}
        </div>
        <a href="${project.link}" class="project-link" target="_blank" rel="noopener noreferrer">${t.project_view}</a>
      </div>
    `;
    projectsList.appendChild(article);
  });

  document.getElementById('certsLabel').textContent = t.certs_label;
  document.getElementById('certsHeading').textContent = t.certs_title;
  document.getElementById('certsSubtitle').textContent = t.certs_subtitle;

  const certificatesList = document.getElementById('certificatesList');
  certificatesList.innerHTML = '';
  data.certificates.forEach((certificate, index) => {
    const article = document.createElement('article');
    article.className = 'project-card reveal';
    article.style.transitionDelay = `${Math.min(index * 0.1, 0.4)}s`;
    article.innerHTML = `
      <div class="project-card-accent" aria-hidden="true" style="background: var(--primary-light);"></div>
      <div class="project-card-body">
        <div class="project-card-number">${String(index + 1).padStart(2, '0')}</div>
        <h3>${certificate.title[lang]}</h3>
        <p><strong>${t.nav_projects}</strong> ${certificate.issuer}${certificate.issue_date ? ` • ${certificate.issue_date}` : ''}</p>
        ${certificate.link && certificate.link !== '#' ? `<a href="${certificate.link}" class="project-link" target="_blank" rel="noopener noreferrer">${t.project_view}</a>` : ''}
      </div>
    `;
    certificatesList.appendChild(article);
  });

  document.getElementById('contactLabel').textContent = t.nav_contact;
  document.getElementById('contactHeading').textContent = t.contact_title;
  document.getElementById('contactText').textContent = t.contact_text;
  document.getElementById('footerText').textContent = t.footer_text;

  const socialLinks = document.getElementById('socialLinks');
  socialLinks.innerHTML = '';
  data.social.forEach((item) => {
    const a = document.createElement('a');
    a.href = item.url;
    a.className = 'contact-social-link';
    a.target = '_blank';
    a.rel = 'noopener noreferrer';
    a.textContent = item.name;
    socialLinks.appendChild(a);
  });

  document.querySelectorAll('.lang-switch a').forEach((link) => {
    link.classList.toggle('active', link.dataset.lang === lang);
  });
}

async function initPortfolio() {
  const data = await loadPortfolioData();
  if (!data) return;
  renderPortfolio(data);
}

initPortfolio();

document.querySelectorAll('.lang-switch a').forEach((link) => {
  link.addEventListener('click', (event) => {
    event.preventDefault();
    const lang = link.dataset.lang;
    localStorage.setItem('portfolio-lang', lang);
    loadPortfolioData().then((data) => data && renderPortfolio(data));
  });
});
