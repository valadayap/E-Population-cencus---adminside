<?php
// Database connection
$host = "localhost";
$user = "root";   // change if needed
$pass = "";       // change if needed
$db   = "cencus";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ SELECT query to group ages
$sql = "
    SELECT 
        CASE
            WHEN age BETWEEN 0 AND 9 THEN '0-9'
            WHEN age BETWEEN 10 AND 19 THEN '10-19'
            WHEN age BETWEEN 20 AND 29 THEN '20-29'
            WHEN age BETWEEN 30 AND 39 THEN '30-39'
            WHEN age BETWEEN 40 AND 49 THEN '40-49'
            WHEN age BETWEEN 50 AND 59 THEN '50-59'
            WHEN age BETWEEN 60 AND 69 THEN '60-69'
            WHEN age BETWEEN 70 AND 79 THEN '70-79'
            ELSE '80+'
        END AS age_group,
        COUNT(*) AS total
    FROM information
    GROUP BY age_group
    ORDER BY MIN(age)
";

$result = $conn->query($sql);

$ageGroups = [];
$totals = [];

while ($row = $result->fetch_assoc()) {
    $ageGroups[] = $row['age_group'];
    $totals[] = $row['total'];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Population by Age Groups</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fff8f0;
      text-align: center;
      margin: 30px;
    }
    h2 {
      color: #ff6600;
    }
    .chart-container {
      width: 80%;
      margin: auto;
      background: #fff;
      border: 2px solid #ff6600;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(255, 102, 0, 0.5);
    }
    canvas {
      margin-top: 20px;
    }
     .container {
    background-color: #f7e0d1ff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 1200px;
    width: 100%;
}  

header h1 {
    font-size: 24px;
    color: #004d40;
    margin-bottom: 5px;
    text-align: center;
}

header p {
    font-size: 14px;
    color: #666;
    text-align: center;
    margin-bottom: 30px;
}

.search-form {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
}

.form-row {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}

.data-display {
    text-align: center;
}
  </style>
</head>
<body>
       <center>
      <div class="container">
        <header>
            <h1>PRIMARY CENSUS ABSTRACT INDICATORS SEARCH UPTO TOWN / VILLAGE LEVEL</h1>
            <p>This page displays the Abstract Population Information based on the selected Indicators.</p>
        </header>

        <section class="search-form">
            <form id="census-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="census-year">Census Year</label>
                        <select id="census-year" name="census-year">
                            <option value="2011">2031</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="state">State/UT</label>
                        <select id="state" name="state">
                            <option value="india">gujarat</option>
                        </select>
                    </div>
                    <div class="form-group">
                       <label for="district">District</label>
                            <select id="district">
                            <option value="">-- Select District --</option>
                                            <option value=" ">Select a District</option>
            <option value="Ahmedabad">Ahmedabad</option>
            <option value="Amreli">Amreli</option>
            <option value="Anand">Anand</option>
            <option value="Aravalli">Aravalli</option>
            <option value="Banaskantha">Banaskantha</option>
            <option value="Bharuch">Bharuch</option>
            <option value="Bhavnagar">Bhavnagar</option>
            <option value="Botad">Botad</option>
            <option value="Chhotaudepur">Chhota Udaipur</option>
            <option value="Dahod">Dahod</option>
            <option value="Dangs">Dangs</option>
            <option value="DevbhoomiDwarka">Devbhumi Dwarka</option>
            <option value="Gandhinagar">Gandhinagar</option>
            <option value="Girsomnath">Gir Somnath</option>
            <option value="Jamnagar">Jamnagar</option>
            <option value="Junagadh">Junagadh</option>
            <option value="Kutch">Kachchh</option>
            <option value="kheda">Kheda</option>
            <option value="Mehsana">Mahesana</option>
            <option value="mahisagar">Mahisagar</option>
            <option value="morbi">Morbi</option>
            <option value="narmada">Narmada</option>
            <option value="navsari">Navsari</option>
            <option value="panchmahal">Panch Mahals</option>
            <option value="patan">Patan</option>
            <option value="porbandar">Porbandar</option>
            <option value="rajkot">Rajkot</option>
            <option value="sabarkantha">Sabarkantha</option>
            <option value="surat">Surat</option>
            <option value="surendranagar">Surendranagar</option>
            <option value="tapi">Tapi</option>
            <option value="vadodara">Vadodara</option>
            <option value="valsad">Valsad</option>
                            </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="subDistrict">Sub-district (Taluka)</label>
                            <select id="subDistrict" disabled>
                            <option value="">-- Select Sub-district --</option>
                            </select>

<script>
const gujaratTalukas = {
  "Ahmedabad": ["Ahmedabad City", "Daskroi", "Dholka", "Sanand", "Viramgam", "Bavla", "Dhandhuka", "Ranpur", "Detroj‑Rampura", "Barwala", "Mandal"],  // :contentReference[oaicite:0]{index=0}
  "Amreli": ["Amreli", "Babra", "Bagasara", "Dhari", "Jafrabad", "Khambha", "Kunkavav Vadia", "Lathi", "Lilia", "Rajula", "Savarkundla"],  // :contentReference[oaicite:1]{index=1}
  "Anand": ["Anand", "Anklav", "Borsad", "Khambhat", "Petlad", "Sojitra", "Tarapur", "Umreth"],  // :contentReference[oaicite:2]{index=2}
  "Aravalli": ["Bayad", "Bhiloda", "Dhansura", "Malpur", "Meghraj", "Modasa"],  // :contentReference[oaicite:3]{index=3}
  "Banaskantha": ["Amirgadh", "Bhabhar", "Dantiwada", "Deesa", "Deodar", "Dhanera", "Kankrej", "Lakhani", "Palanpur", "Suigam", "Tharad", "Vadgam", "Vav"],  // :contentReference[oaicite:4]{index=4}
  "Bharuch": ["Bharuch", "Amod", "Ankleshwar", "Hansot", "Jambusar", "Jhagadia", "Netrang", "Valia"],  // :contentReference[oaicite:5]{index=5}
  "Bhavnagar": ["Bhavnagar", "Mahuva", "Talaja", "Botad", "Palitana", "Sihor", "Gadhada", "Gariadhar", "Ghogha", "Umrala", "Vallabhipur"],  // :contentReference[oaicite:6]{index=6}
  "Botad": ["Botad", "Barwala", "Gadhada", "Ranpur"],  // :contentReference[oaicite:7]{index=7}
  "Chhotaudepur": ["Chhota Udaipur", "Jetpur Pavi", "Kawant", "Naswadi", "Sankheda", "Bodeli"],  // :contentReference[oaicite:8]{index=8}
  "Dahod": ["Dahod", "Devgadbaria", "Dhanpur", "Fatepura", "Garbada", "Jhalod", "Limkheda", "Sanjeli", "Singvad"],  // :contentReference[oaicite:9]{index=9}
  "Dangs": ["Ahwa", "Subir", "Waghai"],  // :contentReference[oaicite:10]{index=10}
  "DevbhoomiDwarka": ["Dwarka", "Bhanvad", "Kalyanpur", "Khambhalia"],  // :contentReference[oaicite:11]{index=11}
  "Gandhinagar": ["Gandhinagar", "Dehgam", "Kalol", "Mansa"],  // :contentReference[oaicite:12]{index=12}
  "Girsomnath": ["Gir Gadhada", "Kodinar", "Sutrapada", "Talala", "Una", "Veraval"],  // :contentReference[oaicite:13]{index=13}
  "Jamnagar": ["Jamnagar", "Jamjodhpur", "Jodiya", "Lalpur", "Kalavad", "Dhrol", "Okhamandal", "Khambhalia"],  // :contentReference[oaicite:14]{index=14}
  "Junagadh": ["Junagadh City", "Junagadh Rural", "Keshod", "Mangrol", "Malia‑Hatina", "Mendarda", "Vanthali", "Visavadar", "Manavadar", "Bhesan"],  // :contentReference[oaicite:15]{index=15}
  "Kheda": ["Kheda", "Nadiad", "Kathlal", "Mahudha", "Kapadvanj", "Thasra", "Galteshwar", "Matar", "Vaso"],  // :contentReference[oaicite:16]{index=16}
  "Kutch": ["Bhuj", "Gandhidham", "Anjar", "Abdasa (Naliya)", "Mandvi", "Mundra", "Rapar", "Bhachau", "Nakhatrana", "Lakhpat"],  // :contentReference[oaicite:17]{index=17}
  "Mahisagar": ["Balasinor", "Kadana", "Khanpur", "Lunawada", "Santrampur", "Virpur"],  // :contentReference[oaicite:18]{index=18}
  "Mehsana": ["Mehsana", "Kadi", "Unjha", "Visnagar", "Vijapur", "Kheralu", "Satlasana", "Becharaji", "Jotana"],  // :contentReference[oaicite:19]{index=19}
  "Morbi": ["Morbi", "Tankara", "Wankaner", "Maliya Miyana", "Halvad"],  // :contentReference[oaicite:20]{index=20}
  "Narmada": ["Dediapada", "Garudeshwar", "Nandod", "Sagbara", "Tilakwada"],  // :contentReference[oaicite:21]{index=21}
  "Navsari": ["Navsari","Chikhli","Gandevi","Jalalpore","Khergam","Bansda"],  // :contentReference[oaicite:22]{index=22}
  "Panchmahal": ["Godhra","Halol","Kalol","Ghoghamba","Jambughoda","Shehra","Morwa‑Hadaf"],  // :contentReference[oaicite:23]{index=23}
  "Patan": ["Patan","Radhanpur","Siddhpur","Chanasma","Harij","Sami","Sarswati","Sankheshwar"],  // :contentReference[oaicite:24]{index=24}
  "Porbandar": ["Porbandar","Ranavav","Kutiyana"],  // :contentReference[oaicite:25]{index=25}
  "Rajkot": ["Rajkot","Morbi","Jasdan","Gondal","Jetpur","Upleta","Dhoraji","Kotda Sangani","Paddhari","Lodhika","Vinchhiya"],  // :contentReference[oaicite:26]{index=26}
  "Sabarkantha": ["Himatnagar","Khedbrahma","Idar","Prantij","Talod","Poshina","Vijaynagar"],  // :contentReference[oaicite:27]{index=27}
  "Surat": ["Surat City","Chorasi","Bardoli","Mangrol","Olpad","Palsana","Mandvi","Kamrej","Mahuva","Umarpada"],  // :contentReference[oaicite:28]{index=28}
  "Surendranagar": ["Surendranagar","Wadhwan","Limbdi","Chotila","Thangadh","Dasada","Lakhtar","Muli","Sayla"],  // :contentReference[oaicite:29]{index=29}
  "Tapi": ["Vyara","Songadh","Nizar","Valod","Uchhal"],  // :contentReference[oaicite:30]{index=30}
  "Vadodara": ["Vadodara City","Padra","Karjan","Savli","Sinor","Vaghodia"],  // :contentReference[oaicite:31]{index=31}
  "Valsad": ["Valsad","Vapi","Pardi","Dharampur","Umbergaon"],  // :contentReference[oaicite:32]{index=32}
};

// Example usage with dropdowns
document.addEventListener('DOMContentLoaded', () => {
  const districtSelect = document.getElementById('district');
  const subDistrictSelect = document.getElementById('subDistrict');

  // Populate district dropdown
  Object.keys(gujaratTalukas).forEach(district => {
    const opt = document.createElement('option');
    opt.value = district;
    opt.textContent = district;
    districtSelect.appendChild(opt);
  });

  // On district change -> populate sub-district dropdown
  districtSelect.addEventListener('change', function() {
    const selected = districtSelect.value;
    subDistrictSelect.innerHTML = '<option value="">-- Select Sub‑District --</option>';
    subDistrictSelect.disabled = true;

    if (selected && gujaratTalukas[selected]) {
      gujaratTalukas[selected].forEach(td => {
        const o = document.createElement('option');
        o.value = td;
        o.textContent = td;
        subDistrictSelect.appendChild(o);
      });
      subDistrictSelect.disabled = false;
    }
  });
});
</script>

                    </div>
                    <div class="form-group">
                        <label for="rural-urban">Rural / Urban</label>
                        <select id="rural-urban" name="rural-urban">
                            <option value="all">All</option>
                            <option value="rural">Rural</option>
                            <option value="urban">Urban</option>
                        </select>
                    </div>
      
                </div>
            </form>
</section>
</center>
  <h2>Population Distribution by Age Groups</h2>
  <div class="chart-container">
    <canvas id="ageChart"></canvas>
  </div>

  <script>
    const ctx = document.getElementById('ageChart').getContext('2d');
    const ageChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($ageGroups); ?>,
        datasets: [{
          label: 'Population Count',
          data: <?php echo json_encode($totals); ?>,
          backgroundColor: 'rgba(255, 102, 0, 0.7)',
          borderColor: 'rgba(255, 102, 0, 1)',
          borderWidth: 2,
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { 
            labels: { color: '#ff6600', font: { weight: 'bold' } } 
          },
          tooltip: { 
            backgroundColor: '#ff6600',
            titleColor: '#fff',
            bodyColor: '#fff'
          }
        },
        scales: {
          x: { ticks: { color: '#ff6600' } },
          y: { 
            ticks: { color: '#ff6600' },
            beginAtZero: true 
          }
        }
      }
    });
  </script>
</body>
</html>