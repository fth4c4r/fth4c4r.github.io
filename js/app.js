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
