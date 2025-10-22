document.addEventListener("DOMContentLoaded", function () {
  // Get all epasscard dropdown containers
  // const containers = document.querySelectorAll(".epasscard-dropdown-container");

  // containers.forEach((container) => {
  //   const toggle = container.querySelector(".dropdown-toggle");

  //   toggle.addEventListener("click", function (e) {
  //     e.stopPropagation(); // Prevent this click from triggering the document click handler
  //     container.classList.toggle("open");
  //   });

  //   // Close when clicking outside any dropdown
  //   document.addEventListener("click", function (e) {
  //     if (!container.contains(e.target)) {
  //       container.classList.remove("open");
  //     }
  //   });
  // });

  // Epasscard admin main tab control
  const tabButtons = document.querySelectorAll(".epasscard-tablinks");
  const tabContents = document.querySelectorAll(".epasscard-tabcontent");

  function openPassTemplate(evt, tabName) {
    tabContents.forEach((content) => {
      content.style.display = "none";
    });

    tabButtons.forEach((button) => {
      button.classList.remove("active");
    });

    const activeTab = document.getElementById(tabName);
    if (activeTab) {
      activeTab.style.display = "block";
    }

    evt.currentTarget.classList.add("active");
  }

  tabButtons.forEach((button) => {
    button.addEventListener("click", function (evt) {
      const tabName = this.dataset.tab;
      openPassTemplate(evt, tabName);
    });
  });

  // Trigger default tab display
  const defaultTab = document.querySelector(".epasscard-tablinks.active");
  if (defaultTab) {
    defaultTab.click();
  }
});
