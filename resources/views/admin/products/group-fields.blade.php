<x-field name="nama" label="Nama grup" :bag="$bag" :value="$group->nama" class="col-sm-4 col-lg-3" required maxlength="50" />
<x-field name="min_pilih" label="Min pilih" type="number" :bag="$bag" :value="$group->min_pilih" class="col-4 col-sm-2 col-lg-1" min="0" max="20" />
<x-field name="max_pilih" label="Maks pilih" type="number" :bag="$bag" :value="$group->max_pilih" class="col-4 col-sm-2 col-lg-1" min="1" max="20" />
<x-field name="urutan" label="Urutan" type="number" :bag="$bag" :value="$group->urutan" class="col-4 col-sm-2 col-lg-1" min="0" max="100" />
<x-field name="wajib" label="Wajib" type="switch" :bag="$bag" :value="$group->wajib" class="col-auto" />
