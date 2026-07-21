// PROJELER 
fetch("data/projects.json")
.then(response => response.json())
.then(projects => {

    const container =
        document.getElementById("projectContainer");

    projects.forEach(project => {

        container.innerHTML += `
        <div class="project-card">

            <img src="${project.image}"
                 alt="${project.title}">

            <div class="project-content">

                <h3>${project.title}</h3>

                <p>${project.description}</p>

                <a href="${project.url}"
                   class="project-btn"
                   target="_blank">
                   İncele
                </a>

            </div>

        </div>`;
    });
})
.catch(error => {
    console.error(error);
});

// BELGELER/SERTİFİKALAR 
fetch("data/achievements.json")
.then(response => response.json())
.then(achievements => {

    const container =
        document.getElementById("achievementContainer");

    achievements.forEach(achievement => {

        container.innerHTML += `
        <div class="project-card">

            <div class="project-content">

                <h3>${achievement.title}</h3>
                <p>${achievement.description}</p>

                <a href="${achievement.url}"
                   class="project-btn"
                   target="_blank">
                   İncele
                </a>

            </div>

        </div>`;
    });
})
.catch(error => {
    console.error(error);
});


// YETENEKLER 
fetch("data/skills.json")
.then(response => response.json())
.then(skills => {

    const container =
        document.getElementById("skillContainer");

    skills.forEach(skill => {
        const percentage = parseInt(skill.description.replace('%', ''), 10) || 0;

        container.innerHTML += `
        <div class="skill-item">
            <div class="skill-title">
                <span>${skill.title}</span>
                <span>${skill.description}</span>
            </div>
            <div class="skill-bar"><span class="skill-fill" data-percentage="${percentage}" style="width:0%"></span></div>
        </div>`;
    });

    const fills = container.querySelectorAll('.skill-fill');
    fills.forEach(fill => {
        const percentage = fill.dataset.percentage || 0;
        requestAnimationFrame(() => {
            fill.style.width = `${percentage}%`;
        });
    });
})
.catch(error => {
    console.error(error);
});
