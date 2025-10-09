# ShopOffice Module

ShopOffice je Yii 1.x modul za upravljanje računima, artiklima i prodajnim izvještajima. Omogućava kreiranje računa s detaljnim stavkama, upravljanje artiklima i generiranje prodajnih izvještaja.

## 🚀 Pokretanje

### Preduslovi
- PHP 7.2+
- MySQL/MariaDB
- Yii Framework 1.1.x
- Web server (Apache/Nginx)

### Instalacija

1. **Kloniraj modul u protected/modules direktorij:**
```bash
cd protected/modules
git clone [repository-url] shopOffice
```

2. **Konfiguriši glavnu konfiguraciju** (`protected/config/main.php`):
```php
'modules' => array(
    'shopOffice' => array(
        'class' => 'application.modules.shopOffice.ShopOfficeModule',
    ),
),
```

3. **Konfiguriši bazu podataka** u glavnoj aplikaciji (`protected/config/main.php`):
```php
'db' => array(
	'connectionString' => 'mysql:host=localhost;dbname=your_database',
	'emulatePrepare' => true,
	'username' => 'your_username',
	'password' => 'your_password',
	'charset' => 'utf8',
),
```

4. **Pokreni migracije:**

```bash
cd protected
php yiic migrate --migrationPath=modules.shopOffice.migrations
```


## 📋 Primjeri glavnih akcija

### Kreiranje novog artikla
```
URL: /shopOffice/item/create
- Unesite naziv, šifru, cijenu i PDV stopu
- Sačuvajte za korištenje u računima
```

### Kreiranje računa
```
URL: /shopOffice/invoice/create
- Unesite osnovne podatke (broj, datum, način plaćanja)
- Dodajte stavke računa odabirom artikala
- Količina i cijene se automatski kalkulišu
- Status: 'draft' (može se mijenjati) ili 'closed' (finalan)
```

### Upravljanje računima
```
URL: /shopOffice/invoice/admin
- Pregled svih računa s mogućnostima filtriranja
- Pretraživanje po broju, datumu, statusu
- Sortiranje i paginacija
```

### Prodajni izvještaj
```
URL: /shopOffice/report/salesReport
- Odaberite period (datum od/do)
- Generirajte izvještaj s ukupnim prodajama
- Prikazuje osnovicu, PDV, PP i ukupne iznose
```

## 🏗️ Organizacija koda i ključne odluke

### Struktura modula
```
shopOffice/
├── ShopOfficeModule.php          # Definicija modula
├── components/
│   └── InvoiceService.php        # Servis za složenu logiku računa
├── controllers/
│   ├── DefaultController.php     # Početna stranica modula
│   ├── InvoiceController.php     # CRUD operacije za račune
│   ├── ItemController.php        # CRUD operacije za artikle
│   └── ReportController.php      # Prodajni izvještaji
├── models/
│   ├── Invoice.php               # Model računa
│   ├── InvoiceLine.php          # Model stavke računa
│   └── Item.php                 # Model artikla
├── views/                       # View fajlovi organizovani po kontrolerima
└── migrations/                  # Migracije baze podataka
```

### Ključne projektne odluke

#### 1. **Modularni pristup**
- Koristi se Yii modularni sistem za enkapsulaciju
- Omogućava lako uključivanje/isključivanje funkcionalnosti
- Nezavisan od glavne aplikacije

#### 2. **Relaciona struktura baze**
- **Invoice** (račun) - glavni entitet
- **InvoiceLine** (stavka računa) - HAS_MANY relacija s računom
- **Item** (artikal) - master data za stavke
- Koriste se foreign key veze za integritet podataka

#### 3. **Status workflow za račune**
- `draft` - račun se može mijenjati
- `closed` - finalni račun, zaštićen od promjena
- Implementirano u `beforeSave()` metodi

#### 4. **Automatska kalkulacija totala**
- `updateTotals()` metoda automatski kalkuliše:
  - Osnovicu (total_net)
  - PDV (total_vat) 
  - PP (total_pp)
  - Ukupno (total_gross)
- Poziva se prije svakeg spremanja računa

#### 5. **Servisi za složenu logiku**
- `InvoiceService` enkapsulira kompleksnu poslovnu logiku
- Razdvojeno od kontrolera (separation of concerns)
- Lakše testiranje i održavanje

#### 6. **Konsistentne konvencije**
- Slijede se Yii konvencije za imenovanje
- ActiveRecord pattern za modele
- MVC arhitektura
- RESTful URL struktura

#### 7. **Lokalizacija**
- `attributeLabels()` na bosanskom/hrvatskom jeziku
- Formatiranje brojeva u lokalnom formatu
- Valuta u KM (Konvertibilne marke)

#### 8. **Search funkcionalnost**
- Implementiran `search()` metod za GridView
- Omogućava filtriranje po svim relevantnim poljima
- Paginacija i sortiranje

#### 9. **Transakcijska sigurnost**
- Koriste se database transakcije za složene operacije
- Rollback u slučaju greške
- Osigurava konzistentnost podataka

#### 10. **Reporting sistem**
- Odvojeni ReportController za izvještaje
- Parametrizovani izvještaji po datumu

### Buduća proširenja
- Export u PDF/Excel format
- Email slanje računa
- Inventory tracking
- Multi-currency podrška
- Audit trail za promjene

