<?php

/**
 * Seeder principal de la base de données
 *
 * Ce seeder remplit la base de données avec des données de test
 * pour le développement et les démonstrations.
 */
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Equipe;
use App\Models\Professor;
use App\Models\Publication;
use Illuminate\Support\Facades\Hash;

/**
 * Classe DatabaseSeeder
 *
 * Seeder pour initialiser la base de données avec des données de test
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Exécuter le seeding de la base de données
     *
     * Cette méthode crée des données de test pour tous les modèles
     * de l'application : admins, professeurs, équipes et publications.
     */
    public function run(): void
    {
        // ==================== 1. CRÉATION DES ADMINS ====================
        
        // Admin principal
        Admin::firstOrCreate([
            'email' => 'admin@emsi.ma',
        ], [
            'name' => 'Admin EMSI',
            'password' => Hash::make('admin123'),
        ]);

        // Admins supplémentaires
        Admin::factory()->count(2)->create();
        
        $this->command->info('✅ Admins créés');
        $this->command->info('   Admin principal: admin@emsi.ma / admin123');

        // ==================== 2. CRÉATION DES PROFESSEURS ====================
        
        // Professeur de test direct
        Professor::create([
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'email' => 'prof@emsi.ma',
            'password' => Hash::make('password123'),
            'grade' => 'Docteur',
            'domaine' => 'Informatique',
        ]);

        // Professeurs supplémentaires via factory
        $professeurs = Professor::factory()->count(5)->create();
        
        $this->command->info('✅ Professeurs créés');
        $this->command->info('   Professeur test: prof@emsi.ma / password123');

        // ==================== 3. CRÉATION DES ÉQUIPES ====================
        
        $equipes = Equipe::factory()->count(3)->create();
        
        $this->command->info('✅ Équipes créées');

        // ==================== 4. ASSIGNATION DES ÉQUIPES AUX PROFESSEURS ====================
        
        $allProfesseurs = Professor::all();
        
        $allProfesseurs->each(function ($professeur) use ($equipes) {
            // Assigner une équipe aléatoire à chaque professeur
            $professeur->equipe_id = $equipes->random()->id;
            $professeur->save();
        });
        
        $this->command->info('✅ Équipes assignées aux professeurs');

        // ==================== 5. ASSIGNATION DES CHEFS D\'ÉQUIPE ====================
        
        $equipes->each(function ($equipe) use ($allProfesseurs) {
            // Récupérer les professeurs de cette équipe
            $professeursEquipe = $allProfesseurs->where('equipe_id', $equipe->id);
            
            if ($professeursEquipe->count() > 0) {
                // Choisir un chef d'équipe aléatoire parmi les membres
                $equipe->id_chef_equipe = $professeursEquipe->random()->id;
                $equipe->save();
            }
        });
        
        $this->command->info('✅ Chefs d\'équipe assignés');

        // ==================== 6. CRÉATION DES PUBLICATIONS ====================
        
        $publications = Publication::factory()->count(10)->create([
            'auteur_principal_id' => function() use ($allProfesseurs) {
                return $allProfesseurs->random()->id;
            },
        ]);
        
        $this->command->info('✅ Publications créées');

        // ==================== 7. AJOUT DES CO-AUTEURS (OPTIONNEL) ====================
        /*
        $publications->each(function ($publication) use ($allProfesseurs) {
            // Choisir 0 à 3 co-auteurs aléatoires (différents de l'auteur principal)
            $coAuteurs = $allProfesseurs
                ->where('id', '!=', $publication->auteur_principal_id)
                ->random(rand(0, 3));
                
            // Attacher les co-auteurs
            $publication->coAuteurs()->attach($coAuteurs);
        });
        
        $this->command->info('✅ Co-auteurs ajoutés');
        */

        // ==================== 8. RÉCAPITULATIF ====================
        
        $this->command->info('========================================');
        $this->command->info('🎉 SEEDING TERMINÉ AVEC SUCCÈS !');
        $this->command->info('========================================');
        $this->command->info('📊 Statistiques :');
        $this->command->info('   • Admins : ' . Admin::count());
        $this->command->info('   • Professeurs : ' . Professor::count());
        $this->command->info('   • Équipes : ' . Equipe::count());
        $this->command->info('   • Publications : ' . Publication::count());
        $this->command->info('');
        $this->command->info('🔐 Identifiants de test :');
        $this->command->info('   ADMIN : admin@emsi.ma / admin123');
        $this->command->info('   PROFESSEUR : prof@emsi.ma / password123');
        $this->command->info('========================================');
    }
}