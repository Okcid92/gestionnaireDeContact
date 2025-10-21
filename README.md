# 📱 Gestionnaire de Contacts Laravel

Une application web complète de gestion de contacts développée avec Laravel, implémentant une architecture N-tier avec authentification, CRUD complet et gestion de groupes.

## 🚀 Fonctionnalités

- ✅ **Authentification complète** (inscription, connexion, déconnexion)
- ✅ **CRUD Contacts** (créer, lire, modifier, supprimer)
- ✅ **CRUD Groupes** (créer, lire, modifier, supprimer)
- ✅ **Relations Many-to-Many** entre contacts et groupes
- ✅ **Filtrage et recherche** des contacts
- ✅ **Pagination** des listes
- ✅ **Sécurité** avec policies et middleware
- ✅ **Interface responsive** avec Tailwind CSS

## 🏗️ Architecture N-Tier

### 1. Couche Présentation (Views)
```php
// resources/views/contacts/index.blade.php
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto">
            <!-- Interface utilisateur -->
        </div>
    </div>
</x-app-layout>
```

### 2. Couche Logique Métier (Controllers)
```php
// app/Http/Controllers/ContactController.php
class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
        ]);

        $contact = auth()->user()->contacts()->create($validated);
        
        if ($request->filled('groups')) {
            $contact->groups()->sync($request->groups);
        }

        return redirect()->route('contacts.index')
            ->with('success', 'Contact créé avec succès.');
    }
}
```

### 3. Couche Accès aux Données (Models)
```php
// app/Models/Contact.php
class Contact extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'address', 'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
```

## 📊 Structure de la Base de Données

### Tables Principales

#### Users
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Contacts
```sql
CREATE TABLE contacts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_email (user_id, email),
    INDEX idx_user_lastname (user_id, last_name)
);
```

#### Groups
```sql
CREATE TABLE groups (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_name_user (name, user_id)
);
```

#### Contact_Group (Table Pivot)
```sql
CREATE TABLE contact_group (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    contact_id BIGINT NOT NULL,
    group_id BIGINT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (contact_id) REFERENCES contacts(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
    UNIQUE KEY unique_contact_group (contact_id, group_id)
);
```

## 🔐 Sécurité

### Policies
```php
// app/Policies/ContactPolicy.php
class ContactPolicy
{
    public function view(User $user, Contact $contact): bool
    {
        return $user->id === $contact->user_id;
    }

    public function update(User $user, Contact $contact): bool
    {
        return $user->id === $contact->user_id;
    }
}
```

### Middleware d'Authentification
```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('contacts', ContactController::class);
    Route::resource('groups', GroupController::class);
});
```

### Validation des Données
```php
$validated = $request->validate([
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'email' => 'required|email|unique:contacts,email',
    'phone' => 'nullable|string|max:20',
    'address' => 'nullable|string',
    'groups' => 'array',
    'groups.*' => 'exists:groups,id',
]);
```

## 🔄 Relations Eloquent

### Relation Many-to-Many
```php
// Dans le modèle Contact
public function groups(): BelongsToMany
{
    return $this->belongsToMany(Group::class);
}

// Dans le modèle Group
public function contacts(): BelongsToMany
{
    return $this->belongsToMany(Contact::class);
}

// Utilisation
$contact = Contact::find(1);
$contact->groups()->attach([1, 2, 3]); // Attacher aux groupes
$contact->groups()->sync([2, 4]);      // Synchroniser avec les groupes
$contact->groups()->detach();          // Détacher tous les groupes
```

### Relation One-to-Many
```php
// Dans le modèle User
public function contacts()
{
    return $this->hasMany(Contact::class);
}

// Dans le modèle Contact
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

## 🔍 Recherche et Filtrage

```php
public function index(Request $request): View
{
    $query = auth()->user()->contacts()->with('groups');
    
    // Filtrage par groupe
    if ($request->filled('group')) {
        $query->whereHas('groups', function ($q) use ($request) {
            $q->where('groups.id', $request->group);
        });
    }
    
    // Recherche textuelle
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
    
    $contacts = $query->paginate(10);
    return view('contacts.index', compact('contacts'));
}
```

## 📋 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

### Étapes d'Installation

1. **Cloner le projet**
```bash
git clone <repository-url>
cd gstionnaireDContact
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configuration de la base de données**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestionnairedecontact
DB_USERNAME=root
DB_PASSWORD=
```

5. **Créer la base de données**
```sql
CREATE DATABASE gestionnairedecontact CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. **Installer l'authentification**
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm run build
```

7. **Exécuter les migrations**
```bash
php artisan migrate
```

8. **Créer des utilisateurs de test**
```bash
php artisan db:seed --class=UserSeeder
```

9. **Lancer le serveur**
```bash
php artisan serve
```

## 🎯 Utilisation

### Comptes de Test
- **Admin**: admin0@example.com / password
- **Test**: test1@example.com / password

### Fonctionnalités Principales

1. **Connexion**: Accédez à `/login`
2. **Gestion des contacts**: Créez, modifiez, supprimez des contacts
3. **Gestion des groupes**: Organisez vos contacts en groupes
4. **Recherche**: Filtrez par nom, email ou groupe
5. **Pagination**: Navigation facile dans les listes

## 🛠️ Commandes Artisan Utiles

```bash
# Créer un modèle avec migration et contrôleur
php artisan make:model Contact -mcr

# Créer une migration
php artisan make:migration create_contacts_table

# Créer un contrôleur
php artisan make:controller ContactController --resource

# Créer une policy
php artisan make:policy ContactPolicy --model=Contact

# Créer un seeder
php artisan make:seeder UserSeeder

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📁 Structure du Projet

```
gstionnaireDContact/
├── app/
│   ├── Http/Controllers/
│   │   ├── ContactController.php
│   │   └── GroupController.php
│   ├── Models/
│   │   ├── Contact.php
│   │   ├── Group.php
│   │   └── User.php
│   └── Policies/
│       ├── ContactPolicy.php
│       └── GroupPolicy.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── contacts/
│       └── groups/
└── routes/
    └── web.php
```

## 🔧 Technologies Utilisées

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Base de données**: MySQL 8.0
- **Authentification**: Laravel Breeze
- **Architecture**: MVC + N-Tier
- **ORM**: Eloquent

## 📈 Optimisations

### Indexation Base de Données
```php
// Dans les migrations
$table->index(['user_id', 'email']);
$table->index(['user_id', 'last_name']);
$table->unique(['name', 'user_id']);
```

### Eager Loading
```php
// Éviter le problème N+1
$contacts = auth()->user()->contacts()->with('groups')->paginate(10);
```

### Pagination
```php
// Pagination automatique
$contacts = $query->paginate(10);

// Dans la vue
{{ $contacts->links() }}
```

## 🤝 Contribution

1. Fork le projet
2. Créez une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou problème, ouvrez une issue sur GitHub ou contactez l'équipe de développement.

---

**Développé avec ❤️ en utilisant Laravel**