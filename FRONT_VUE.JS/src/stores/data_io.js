import { defineStore } from 'pinia'
import { ref,computed } from 'vue'
import { useAdmin } from '@/stores/admin'
import { useEtudiant } from '@/stores/etudiant'
import { useToasting } from '@/stores/toasting'
import { useData } from '@/stores/data'
import { useNote } from '@/stores/note'
import strslug from '@/composant/text/slug'
import axios from 'axios'
import { useRoute } from 'vue-router'

export const useDataIO = defineStore('useDataIO', () => {


    const admin=useAdmin()
    const Etudiant=useEtudiant()
    const pending=ref({})
    const Toasting=useToasting()
    const Data=useData()
    const Note=useNote()
    const route=useRoute()

    const getPending=computed(()=>pending.value)

    const exportMoyenneUe=(type,form)=>{
        var payload={}
        payload.type=type
        payload.form=form
        payload.parcour_id=route.params.parcour

        pending.value.export_moyenne_ue=true

        axios({
            url:'/data/export/moyenne-ue',
            method: 'POST',
            data: payload,
            responseType: 'blob'
        }).then((response)=>{
                pending.value.export_moyenne_ue=false
                download(response.data,"moyenne_ue",type);
            })
            .catch((error)=>{
                pending.value.export_moyenne_ue=false
                Toasting.errorDefault("Export moyenne",error)
            })
    }

    const exportMoyenneBase=(payload)=>{
        

        pending.value.export_moyenne_ue=true

        axios.post('/data/export/resultat-base',payload)
            .then((response)=>{
                pending.value.export_moyenne_ue = false
                Toasting.success("Export resulat","Vous verrez une notifications pour le lien de téléchargement");
            })
            .catch((error)=>{
                pending.value.export_moyenne_ue=false
                Toasting.errorDefault("Export moyenne",error)
            })
    }

    const exportMoyenneSemestre=(type,form,data)=>{


        data.type=type
        data.form=form
        data.parcour_id=route.params.parcour

        pending.value.export_moyenne_semestre=true

        axios.post('/data/export/moyenne-semestre',data,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        }).then((response)=>{
            pending.value.export_moyenne_semestre=false
            download(response.data,"moyenne_semestre",type);
        })
        .catch((error)=>{
            pending.value.export_moyenne_semestre=false
            Toasting.errorDefault("Export moyenne",error)
        })

    }

    const importUser=(data)=>{

        if(!confirm("confirmer pour continuer"))
            return

        pending.value.import_user=true
        axios.post('/data/import/user',data)
            .then((response)=>{
                pending.value.import_user=false
                Toasting.success("Importation utilisateur","importation utilisateur succès")
                admin.setAdminList(response.data);
            })
            .catch((error)=>{
                pending.value.import_user=false
                Toasting.errorDefault("Importation utilisateur",error)
            })
    }


    const importEtudiant=(data)=>{

        if(!confirm("confirmer pour continuer"))
            return

        pending.value.import_etudiant=true
        axios.post('/data/import/etudiant',data)
            .then((response)=>{
                pending.value.import_etudiant=false
                Etudiant.setEtudiantList(response.data);
                Toasting.success("Importation utilisateur","importation étudiant succès")
            })
            .catch((error)=>{
                pending.value.import_etudiant=false
                Toasting.errorDefault("Importation étudiant",error)
            })
    }

    const exportPassageAniveau=(extension,type,data={})=>{
        data.type=type
        data.extension = extension
        pending.value.export_passage=true
        axios.post('/data/export/passage',data,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
            .then((response)=>{
                pending.value.export_passage=false
                download(response.data,"passage",extension);
            })
            .catch((error)=>{
                pending.value.export_passage=false
                Toasting.errorDefault("Export passage",error)
            })
    }

    const getUEHead=(type)=>{
        pending.value.import_ue=true
        axios.get('/data/export/ue-head/'+type,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
            .then((response)=>{
                pending.value.import_ue=false
                download(response.data,"template_ue",type);
            })
            .catch((error)=>{
                pending.value.import_ue=false
                Toasting.errorDefault("Export header",error)
            })
    }

    const importUe=(payload)=>{

        pending.value.import_ue=true
        axios.post('/data/import/ue',payload)
            .then((response)=>{
                pending.value.import_ue=false
                Data.setUes(response.data);
                Toasting.success("Importation UE","importation des UE succès")
            })
            .catch((error)=>{
                pending.value.import_ue=false
                Toasting.errorDefault("Importation UE",error)
            })
    }


    const getProfHead=(data)=>{

        pending.value.import_user=true
        axios.get('/data/export/prof-head/'+data,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
            .then((response)=>{
                pending.value.import_user=false
                download(response.data,"template_prof",data);
            })
            .catch((error)=>{
                pending.value.import_user=false
                Toasting.errorDefault("Export Template",error)
            })
    }


    const getEtudiantHead=(data)=>{

        pending.value.import_etudiant=true
        axios.get('/data/export/etudiant-head/'+data,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
            .then((response)=>{
                pending.value.import_etudiant=false
                download(response.data,"template_etudiant",data);
            })
            .catch((error)=>{
                pending.value.import_etudiant=false
                Toasting.errorDefault("Export Template",error)
            })
    }

    const getMatiereHead=(type)=>{

        pending.value.import_matiere=true
        axios.get('/data/export/matiere-head/'+type,{
            responseType: 'blob' // Spécifie le type de réponse attendu comme un objet Blob
        })
        .then((response)=>{
            pending.value.import_matiere=false
            download(response.data,"template_matiere",type);
        })
        .catch((error)=>{
            pending.value.import_matiere=false
            Toasting.errorDefault("Export Template",error)
        })

    }

    const exportTemplateNote=(payload)=>{

        if(!confirm("Veuillez confirmer pour continuer."))
            return

        let parcour=Data.parcours.find(el=>el.id==payload.parcour_id)


        pending.value.export_template_note=true
        axios({
            url:'/data/export/note-template/xlsx',
            method: 'POST',
            data: payload,
            responseType: 'blob'
        })
        .then((response)=>{
            pending.value.export_template_note=false

            download(response.data,`template-note-${parcour?.parcour}`,"xlsx");
        })
        .catch((error)=>{
            pending.value.export_template_note=false
            Toasting.errorDefault("Export Template",error)
        })

    }


    const exportTemplateNoteTP=(payload)=>{

        if(!confirm("Veuillez confirmer pour continuer."))
            return

        let parcour=Data.parcours.find(el=>el.id==payload.parcour_id)

        pending.value.export_template_note_tp=true
        axios({
            url:'/data/export/note-tp-template/xlsx',
            method: 'POST',
            data: payload,
            responseType: 'blob'
        })
        .then((response)=>{
            pending.value.export_template_note_tp=false
            download(response.data,`template-note-tp-${parcour?.parcour}`,"xlsx");
        })
        .catch((error)=>{
            pending.value.export_template_note_tp=false
            Toasting.errorDefault("Export Template",error)
        })

    }

    const exportNoteTP=(payload,type='pdf')=>{

        pending.value.export_note_tp=true
        axios({
            url:'/data/export/download-note-tp/'+type,
            method: 'POST',
            data: payload,
            responseType: 'blob'
        })
        .then((response)=>{
            pending.value.export_note_tp=false
            download(response.data,`note-tp-${payload?.parcour}`,type);
        })
        .catch((error)=>{
            pending.value.export_note_tp=false
            Toasting.errorDefault("Export Template",error)
        })

    }

    

    const importNote=(payload,matiere='',parcour_id='')=>{
        let _matiere=route.params.matiere || matiere
        let parcour=route.params.parcour || parcour_id
        pending.value.import_note=true
        axios.post('/data/import/import-note/'+_matiere,payload)
            .then((response)=>{
                Toasting.success("Importation Note","Importation Note avec Succès");
                pending.value.import_note=false
                Note.setNoteParcour(parcour,response.data)
            }).catch((error)=>{
                Toasting.errorDefault("Importation Note",error,20000)
                pending.value.import_note=false
            })
    }


    const importNoteTP=(payload,tp,parcour_id='')=>{
        let parcour=route.params.parcour || parcour_id
        let tp_id=route.params.tp || tp
        pending.value.import_note_tp=true
        axios.post('/data/import/import-note-tp/'+tp_id,payload)
            .then((response)=>{
                Toasting.success("Importation Note","Importation Note avec Succès");
                pending.value.import_note_tp=false
                Note.setNoteTPParcour(parcour,response.data)
            }).catch((error)=>{
                Toasting.errorDefault("Importation Note",error,20000)
                pending.value.import_note_tp=false
            })
    }

    const importMatiere=(payload)=>{
        pending.value.import_matiere=true
        axios.post('/data/import/matiere',payload)
            .then((response)=>{
                pending.value.import_matiere=false
                Data.setMatieres(response.data);
                Toasting.success("Importation matiere","importation des matiere succès")
            })
            .catch((error)=>{
                pending.value.import_matiere=false
                Toasting.errorDefault("Importation matiere",error)
            })
    }



    /**
     *  private methode 
     */


    const download=(data,nom,type='xlsx')=>{

        let url=''
        type=type.toLowerCase()
        if(type!="pdf"){
            url = window.URL.createObjectURL(new Blob([data]));
        }else{
            url = window.URL.createObjectURL(new Blob([data],{ type: 'application/pdf' }));
        }
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download',`${strslug(nom)}.${type}`); // Nom du fichier de téléchargement
        document.body.appendChild(link);
        link.click();
    }

    return {
        getPending,
        exportTemplateNote,
        exportTemplateNoteTP,
        exportNoteTP,
        exportMoyenneUe,
        exportMoyenneBase,
        exportMoyenneSemestre,
        getProfHead,
        getEtudiantHead,
        getUEHead,
        importUe,
        importMatiere,
        importNote,
        importNoteTP,
        getMatiereHead,
        importUser,
        importEtudiant,
        exportPassageAniveau,
        download
    }

})