export type SongCategory = 'uta' | 'shamisen' | 'both'

export interface OkeikoDataModel {
  category: SongCategory;
  title: string;
}

export interface RecordData {
  recordDate: Date|undefined;
  songList: Array<OkeikoDataModel>;
  remark: string;
}
