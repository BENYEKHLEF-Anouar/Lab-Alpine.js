export default ({ articles, search, createUrl, csrf }) => ({
    articles: articles,
    search: search,
    filterStatus: '',
    loading: false,
    showModal: false,
    editingId: null,
    formData: {
        title: '',
        content: '',
        is_published: false
    },

    async fetchArticles() {
        this.loading = true;
        try {
            const params = new URLSearchParams({
                search: this.search,
                filter_status: this.filterStatus
            });
            const response = await fetch(`?${params.toString()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            this.articles = await response.json();

            // Rafraîchir les icônes après la mise à jour du DOM
            this.$nextTick(() => {
                if (window.lucide) {
                    window.lucide.createIcons({ icons: window.lucide.icons });
                }
            });

        } catch (e) {
            console.error('Erreur lors du chargement des articles:', e);
        } finally {
            this.loading = false;
        }
    },

    openCreateModal() {
        this.editingId = null;
        this.formData = { title: '', content: '', is_published: false };
        this.showModal = true;
    },

    editArticle(article) {
        this.editingId = article.id;
        this.formData = {
            title: article.title,
            content: article.content,
            is_published: Boolean(article.is_published)
        };
        this.showModal = true;
    },

    async submitForm() {
        try {
            const url = this.editingId ? `/articles/${this.editingId}` : createUrl;
            const method = this.editingId ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                body: JSON.stringify(this.formData),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                this.showModal = false;
                if (!this.editingId) {
                    this.search = ''; // Reset search on new item
                }
                this.fetchArticles(); // Reload list
            } else {
                const error = await response.json();
                alert('Erreur lors de la sauvegarde: ' + (error.message || 'Erreur inconnue'));
            }
        } catch (e) {
            console.error('Erreur lors de la sauvegarde:', e);
            alert('Erreur lors de la sauvegarde');
        }
    },

    async deleteArticle(id) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

        try {
            const response = await fetch(`/articles/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                this.fetchArticles();
            } else {
                alert('Erreur lors de la suppression');
            }
        } catch (e) {
            console.error('Erreur lors de la suppression:', e);
            alert('Erreur lors de la suppression');
        }
    }
});