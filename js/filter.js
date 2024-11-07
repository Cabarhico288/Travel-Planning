document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dropdownsearch select').forEach(select => {
      select.addEventListener('change', filterPackages);
    });
    document.getElementById('search-btn').addEventListener('click', filterPackages);
  });
  
  function filterPackages() {
    const activity = document.getElementById('dropdown1').value;
    const flight = document.getElementById('dropdown2').value;
    const accommodation = document.getElementById('dropdown3').value;
    const searchInput = document.getElementById('search-input').value;
  
    const params = new URLSearchParams({ activity, flight, accommodation, searchInput });
  
    fetch(`traveler/filter.php?${params.toString()}`)
      .then(response => response.text())
      .then(data => {
        document.getElementById('display-container').innerHTML = data;
      })
      .catch(error => console.error('Error fetching data:', error));
  }
  