export interface RecordDetail {
  recordDate: undefined|Date;
  songDataList: Array<SongData>;
  remark: string;
}

export type SongCategory = 'uta'  | 'shamisen' | 'both'

export interface SongData {
  category: SongCategory;
  title: string;
}
