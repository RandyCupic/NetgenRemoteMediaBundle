<template>
  <div class="mediaFacets">
    <div class="body">
      <div class="form-field">
        <label for="mediaType">{{ this.$root.$data.NgRemoteMediaTranslations.browse_select_media_type }}</label>
        <v-select
            :options="mediaTypes"
            label="name"
            v-model="selectedMediaType"
            @input="handleTypeChange"
            :reduce="option => option.id"
            :placeholder="this.$root.$data.NgRemoteMediaTranslations.browse_all_media_types"
        />
      </div>

      <div class="form-field">
        <label for="folder">{{ this.$root.$data.NgRemoteMediaTranslations.browse_select_folder }}</label>
        <v-select
          :options="folders"
          label="name"
          v-model="selectedFolder"
          @input="handleFolderChange"
          :reduce="option => option.id"
          :placeholder="this.$root.$data.NgRemoteMediaTranslations.browse_all_folders"
        />
      </div>

      <div class="form-field">
        <label for="tag">{{ this.$root.$data.NgRemoteMediaTranslations.browse_select_tag }}</label>
        <v-select
            :options="tags"
            label="name"
            v-model="tag"
            @input="handleTagChange"
            :reduce="option => option.id"
            :placeholder="this.$root.$data.NgRemoteMediaTranslations.browse_all_tags"
        />
      </div>

      <div class="search-wrapper">
        <span class="search-label">{{ this.$root.$data.NgRemoteMediaTranslations.search }}</span>
        <div class="search">
            <ul class="searchType">
            </ul>
            <input
            type="text"
            :placeholder="this.$root.$data.NgRemoteMediaTranslations.search_placeholder"
            v-model="query"
            @keyup="handleQueryChange"
            @keydown.enter.prevent="null"
            />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  TYPE_ALL,
  TYPE_IMAGE,
  TYPE_VIDEO,
  TYPE_RAW,
  SEARCH_NAME,
  FOLDER_ALL,
  TAG_ALL,
} from "../constants/facets";


import vSelect from "vue-select";

export default {
  name: "MediaFacets",
  props: ["folders", "tags", "facets", "mediaTypes"],
  data() {
    return {
      TYPE_ALL,
      TYPE_IMAGE,
      TYPE_VIDEO,
      TYPE_RAW,
      SEARCH_NAME,
      FOLDER_ALL,
      TAG_ALL,
      selectedFolder: this.facets.folder,
      selectedMediaType: this.facets.mediaType,
      query: this.facets.query
    };
  },
  methods: {
    handleSearchChange(searchType) {
      this.$emit("change", { searchType });
    },
    handleTypeChange(mediaType) {
      this.$emit("change", { mediaType });
    },
    handleFolderChange(value) {
      this.$emit("change", { folder: this.selectedFolder });
    },
    handleQueryChange() {
      this.$emit("change", { query: this.query });
    },
    handleTagChange() {
      this.$emit("change", { tag: this.tag });
    }
  },
  components: {
    "v-select": vSelect
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
@import "../scss/variables";

.mediaFacets {
  width: 362px;
  flex-shrink: 0;
  box-shadow: inset -1px 0 0 0 $mercury;

  .body {
    box-shadow: inset 0 -1px 0 0 $mercury, inset 0 1px 0 0 $mercury, inset -1px 0 0 0 $mercury;
    background: $white;
    padding: 30px 15px;

    .form-field + .form-field {
      margin-top: 15px;
    }

    .form-field label,
    .search-wrapper .search-label {
      font-size: 12px;
      font-weight: 700;
      line-height: 18px;
      color: $boulder;
      margin-bottom: 3px;
      display: block;
    }

    .search-wrapper {
      margin: 30px 0 0;;

      .search {
        display: flex;
        align-items: center;
        margin: 5px 0;

        ul, input {
          font-size: 14px;
          line-height: 16px;
        }

        ul {
          margin: 0;
          padding: 5px;
          list-style: none;
          display: flex;
          align-items: center;
          border: 1px solid $mercury;
          min-width: 75px;
          display: none;

          li {
            cursor: auto;
            margin-right: 10px;
            padding: 4px 10px;
            min-width: 45px;

            &:last-child,
            &:only-child {
              margin: 0;
            }

            &.active {
              background: $netgen-primary;
              color: $white;
              border-radius: 4px;
              box-shadow: inset -1px 0 0 0 $alto, inset 1px 0 0 0 $alto, inset 0 1px 0 0 $alto, inset 0 -1px 0 0 $alto;
            }
          }
        }

        input {
          border: 1px solid $mercury;
          padding: 9px 10px;
          // margin-left: 14px;
          flex-grow: 1;
        }
      }
    }
  }
}
</style>
