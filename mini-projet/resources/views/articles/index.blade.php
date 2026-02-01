@extends('layouts.app')

@section('content')
    <div x-data="articleManager({
            articles: {{ Js::from($articles) }},
            search: '{{ $search ?? '' }}',
            createUrl: '{{ route('articles.store') }}',
            csrf: '{{ csrf_token() }}'
        })">

        <!-- Controls Section -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="relative w-full md:w-1/3 group">
                <input type="text" x-model="search" @input.debounce.300ms="fetchArticles()"
                    class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all duration-200 placeholder:text-gray-400 text-gray-900"
                    placeholder="Rechercher...">
                <i data-lucide="search" class="absolute left-3 top-2.5 h-5 w-5 text-gray-400"></i>

                <!-- Mini spinner -->
                <span x-show="loading" class="absolute right-3 top-2.5" x-transition.opacity>
                    <i data-lucide="loader-2" class="animate-spin h-5 w-5 text-indigo-600"></i>
                </span>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative">
                    <select x-model="filterStatus" @change="fetchArticles()"
                        class="appearance-none pl-4 pr-10 py-2 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition-all">
                        <option value="">Tous les statuts</option>
                        <option value="published">Publiés</option>
                        <option value="draft">Brouillons</option>
                    </select>
                    <i data-lucide="filter" class="absolute right-3 top-2.5 h-4 w-4 text-gray-500 pointer-events-none"></i>
                </div>

                <button @click="openCreateModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm transition-all duration-200 font-medium">
                    <i data-lucide="plus" class="h-5 w-5"></i>
                    <span>Nouveau</span>
                </button>
            </div>
        </div>

        <!-- Loading State (Main) -->
        <div x-show="loading" class="flex justify-center items-center py-20" x-transition.opacity>
            <i data-lucide="loader-2" class="animate-spin h-10 w-10 text-indigo-600"></i>
        </div>

        <!-- Article Grid -->
        <div x-show="!loading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <template x-for="article in articles" :key="article.id">
                <div
                    class="group bg-white rounded-lg p-6 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-indigo-600">
                            <i data-lucide="file-text" class="h-6 w-6"></i>
                        </div>
                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full border" :class="article.is_published
                                    ? 'bg-green-50 text-green-700 border-green-200'
                                    : 'bg-yellow-50 text-yellow-700 border-yellow-200'"
                            x-text="article.is_published ? 'Publié' : 'Brouillon'">
                        </span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors"
                        x-text="article.title"></h3>
                    <p class="text-gray-500 text-sm mb-6 line-clamp-3 flex-grow" x-text="article.content"></p>

                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
                        <button @click="editArticle(article)"
                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-md transition-all"
                            title="Modifier">
                            <i data-lucide="pencil" class="h-4 w-4"></i>
                        </button>
                        <button @click="deleteArticle(article.id)"
                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all"
                            title="Supprimer">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="articles.length === 0" class="col-span-full py-20 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-4">
                    <i data-lucide="inbox" class="h-6 w-6 text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Aucun article trouvé</h3>
                <p class="text-gray-500 mt-1">Essaie de modifier tes filtres ou d'en créer un nouveau.</p>
            </div>
        </div>

        <!-- Modern Modal (Clean) -->
        <div x-show="showModal" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500/75 transition-opacity" @click="showModal = false"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!-- Panel -->
                    <div x-show="showModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                        <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-4"
                                x-text="editingId ? 'Modifier l\'article' : 'Créer un article'"></h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                    <input type="text" x-model="formData.title"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border"
                                        placeholder="Titre de votre article...">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
                                    <textarea x-model="formData.content" rows="4"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border"
                                        placeholder="Écrivez quelque chose..."></textarea>
                                </div>

                                <div class="relative flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="is_published" type="checkbox" x-model="formData.is_published"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_published" class="font-medium text-gray-700">Publier</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                            <button @click="submitForm()" type="button"
                                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                                Sauvegarder
                            </button>
                            <button @click="showModal = false" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection