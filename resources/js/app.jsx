import { useState, useEffect, createContext, useContext } from "react";
import { BookOpen, Star, Users, Award, ChevronRight, Menu, X, LogIn, UserPlus, Home, BarChart2, Upload, Shield, Bell, Search, Moon, Sun, Play, FileText, Mic, Youtube, CheckCircle, Clock, TrendingUp, Book, Settings, LogOut, AlertTriangle, Eye, Trash2, Edit, PlusCircle, DollarSign, Heart, ArrowRight, Globe, Lock, User, Mail, Key, Phone, Calendar, Hash, Filter } from "lucide-react";

const AppCtx = createContext();
const useApp = () => useContext(AppCtx);

const COLORS = {
  primary: "#1B4332",
  accent:  "#D4AF37",
  light:   "#F0FDF4",
  dark:    "#0A1F14",
};

const css = `
  @import url('https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700;800&display=swap');
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family:'Inter',sans-serif; background:#fafaf8; color:#1a1a1a; }
  .arabic { font-family:'Amiri',serif; direction:rtl; }
  ::-webkit-scrollbar { width:6px; } ::-webkit-scrollbar-track { background:#f1f1f1; } ::-webkit-scrollbar-thumb { background:#1B4332; border-radius:3px; }
  .gold-gradient { background: linear-gradient(135deg, #D4AF37, #F0C040, #D4AF37); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  .green-gradient { background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 50%, #1B4332 100%); }
  .card-hover { transition: transform .2s, box-shadow .2s; } .card-hover:hover { transform:translateY(-4px); box-shadow:0 20px 40px rgba(27,67,50,.15); }
  .btn-gold { background:linear-gradient(135deg,#D4AF37,#F0C040); color:#0A1F14; font-weight:700; padding:12px 28px; border-radius:10px; border:none; cursor:pointer; transition:all .2s; font-size:15px; }
  .btn-gold:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(212,175,55,.4); }
  .btn-green { background:#1B4332; color:#fff; font-weight:600; padding:12px 28px; border-radius:10px; border:none; cursor:pointer; transition:all .2s; font-size:15px; }
  .btn-green:hover { background:#2D6A4F; transform:translateY(-2px); box-shadow:0 8px 20px rgba(27,67,50,.3); }
  .nav-link { color:#fff; text-decoration:none; padding:8px 16px; border-radius:8px; transition:all .2s; font-weight:500; cursor:pointer; }
  .nav-link:hover { background:rgba(212,175,55,.2); color:#D4AF37; }
  .input-field { width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:10px; font-size:15px; outline:none; transition:border-color .2s; background:#fff; }
  .input-field:focus { border-color:#1B4332; }
  .badge { display:inline-flex; align-items:center; gap:4px; padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; }
  .badge-gold { background:#FEF9C3; color:#854D0E; }
  .badge-green { background:#DCFCE7; color:#166534; }
  .badge-blue { background:#DBEAFE; color:#1E40AF; }
  .badge-red { background:#FEE2E2; color:#991B1B; }
  .sidebar-link { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:10px; cursor:pointer; transition:all .2s; color:#4B5563; font-weight:500; }
  .sidebar-link:hover, .sidebar-link.active { background:#1B4332; color:#fff; }
  .stat-card { background:#fff; border-radius:16px; padding:24px; border:1px solid #E5E7EB; box-shadow:0 2px 8px rgba(0,0,0,.04); }
  .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; }
  .modal { background:#fff; border-radius:20px; padding:32px; max-width:480px; width:100%; box-shadow:0 40px 80px rgba(0,0,0,.2); }
  .table { width:100%; border-collapse:collapse; } .table th { background:#F9FAFB; padding:12px 16px; text-align:left; font-size:13px; color:#6B7280; font-weight:600; border-bottom:1px solid #E5E7EB; }
  .table td { padding:14px 16px; border-bottom:1px solid #F3F4F6; font-size:14px; vertical-align:middle; }
  .table tr:hover td { background:#F9FAFB; }
  .animate-fade { animation: fadeIn .4s ease; } @keyframes fadeIn { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
  .ayah-card { background: linear-gradient(135deg, #1B4332, #2D6A4F); color:#fff; border-radius:16px; padding:28px; text-align:center; }
  .progress-bar { height:8px; background:#E5E7EB; border-radius:4px; overflow:hidden; }
  .progress-fill { height:100%; background:linear-gradient(90deg,#1B4332,#D4AF37); border-radius:4px; transition:width .8s ease; }
  .hero-bg { background: linear-gradient(160deg, #0A1F14 0%, #1B4332 40%, #2D6A4F 100%); min-height:100vh; position:relative; overflow:hidden; }
  .hero-bg::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5 L55 20 L55 40 L30 55 L5 40 L5 20Z' fill='none' stroke='rgba(212,175,55,0.06)' stroke-width='1'/%3E%3C/svg%3E"); }
  .feature-icon { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#1B4332,#2D6A4F); }
`;

// ── DATA ────────────────────────────────────────────────────────────────────
const programs = [
  { id:1, title:"Hifz Al-Quran — Beginner", titleAr:"حفظ القرآن للمبتدئين", juz:"Juz 30", students:142, level:"free", published:true, desc:"Start your memorization journey with the last juz, focusing on short surahs with tajweed fundamentals." },
  { id:2, title:"Hifz Al-Quran — Intermediate", titleAr:"حفظ القرآن المتوسط", juz:"Juz 28–30", students:89, level:"premium", published:true, desc:"Deepen your memorization across three juz with weekly review sessions and teacher guidance." },
  { id:3, title:"Tajweed Mastery", titleAr:"إتقان التجويد", juz:"Full Rules", students:67, level:"free", published:true, desc:"A complete course on Tajweed rules with audio examples, exercises, and certification." },
  { id:4, title:"Quran for Children", titleAr:"القرآن للأطفال", juz:"Juz 30", students:215, level:"free", published:true, desc:"Age-appropriate, gamified Quran learning designed specifically for children ages 6–12." },
  { id:5, title:"Advanced Hifz Program", titleAr:"برنامج الحفظ المتقدم", juz:"Full Quran", students:31, level:"premium", published:true, desc:"Full Quran memorization with a dedicated teacher, daily review, and progress certification." },
];

const recentLogs = [
  { id:1, surah:"Al-Fatiha", ayah:"1–7", quality:5, date:"2026-02-26", status:"approved" },
  { id:2, surah:"Al-Baqarah", ayah:"1–10", quality:4, date:"2026-02-25", status:"pending" },
  { id:3, surah:"Al-Baqarah", ayah:"11–20", quality:4, date:"2026-02-24", status:"pending" },
  { id:4, surah:"Al-Baqarah", ayah:"21–30", quality:3, date:"2026-02-23", status:"approved" },
  { id:5, surah:"Al-Baqarah", ayah:"31–40", quality:5, date:"2026-02-22", status:"approved" },
];

const adminUsers = [
  { id:1, name:"Ahmed Al-Rashid", email:"ahmed@example.com", role:"student", age_group:"adult", banned:false, joined:"2026-01-10" },
  { id:2, name:"Fatima Hassan", email:"fatima@example.com", role:"teacher", age_group:"adult", banned:false, joined:"2025-12-01" },
  { id:3, name:"Omar Junior", email:"omar.jr@example.com", role:"student", age_group:"child", banned:false, joined:"2026-02-15" },
  { id:4, name:"Ali Mohammed", email:"ali@example.com", role:"student", age_group:"adult", banned:true, joined:"2026-01-20" },
  { id:5, name:"Layla Ibrahim", email:"layla@example.com", role:"center_admin", age_group:"adult", banned:false, joined:"2025-11-15" },
];

const contentItems = [
  { id:1, title:"Tajweed Rules - Part 1", type:"pdf", uploader:"Fatima Hassan", status:"pending", date:"2026-02-25" },
  { id:2, title:"Al-Fatiha Recitation", type:"audio", uploader:"Fatima Hassan", status:"approved", date:"2026-02-24" },
  { id:3, title:"Makharij Al-Huruf", type:"youtube", uploader:"Fatima Hassan", status:"pending", date:"2026-02-23" },
  { id:4, title:"Beginner Workbook", type:"pdf", uploader:"Omar Hesham", status:"approved", date:"2026-02-20" },
];

const dailyAyah = { ar: "وَلَقَدْ يَسَّرْنَا الْقُرْآنَ لِلذِّكْرِ فَهَلْ مِن مُّدَّكِرٍ", en: "And We have certainly made the Quran easy to remember. So is there anyone who will be mindful?", ref: "Al-Qamar 54:17" };

// ── HELPERS ─────────────────────────────────────────────────────────────────
const Stars = ({n}) => Array.from({length:5}).map((_,i) => <span key={i} style={{color: i<n?"#D4AF37":"#D1D5DB"}}>★</span>);
const TypeIcon = ({type}) => ({ pdf:<FileText size={16}/>, audio:<Mic size={16}/>, youtube:<Youtube size={16}/>, image:<Eye size={16}/> }[type] || <FileText size={16}/>);

// ── NAV ─────────────────────────────────────────────────────────────────────
function Navbar({onNav, currentPage, user, onLogout}) {
  const [open, setOpen] = useState(false);
  return (
    <nav style={{background:"rgba(10,31,20,.95)", backdropFilter:"blur(12px)", borderBottom:"1px solid rgba(212,175,55,.15)", position:"sticky", top:0, zIndex:100}}>
      <div style={{maxWidth:1200, margin:"0 auto", padding:"0 24px", height:68, display:"flex", alignItems:"center", justifyContent:"space-between"}}>
        <div onClick={()=>onNav("home")} style={{cursor:"pointer", display:"flex", alignItems:"center", gap:10}}>
          <div style={{width:40, height:40, borderRadius:10, background:"linear-gradient(135deg,#D4AF37,#F0C040)", display:"flex", alignItems:"center", justifyContent:"center"}}>
            <BookOpen size={22} color="#0A1F14" strokeWidth={2.5}/>
          </div>
          <div>
            <div style={{color:"#fff", fontWeight:800, fontSize:17, lineHeight:1}}>Omar Hesham</div>
            <div style={{color:"#D4AF37", fontSize:11, letterSpacing:1}}>.school</div>
          </div>
        </div>
        <div style={{display:"flex", gap:4, alignItems:"center"}}>
          {["home","programs","pricing","donate"].map(p=>(
            <span key={p} className="nav-link" onClick={()=>onNav(p)} style={{textTransform:"capitalize", color: currentPage===p?"#D4AF37":"#fff"}}>{p}</span>
          ))}
          {!user ? (
            <>
              <button className="btn-green" onClick={()=>onNav("login")} style={{padding:"8px 18px", fontSize:14, marginLeft:8}}>Login</button>
              <button className="btn-gold" onClick={()=>onNav("register")} style={{padding:"8px 18px", fontSize:14}}>Sign Up</button>
            </>
          ) : (
            <div style={{display:"flex", alignItems:"center", gap:8, marginLeft:8}}>
              <div style={{width:36, height:36, borderRadius:"50%", background:"linear-gradient(135deg,#D4AF37,#F0C040)", display:"flex", alignItems:"center", justifyContent:"center", cursor:"pointer"}} onClick={()=>onNav("dashboard")}>
                <span style={{fontWeight:700, fontSize:14, color:"#0A1F14"}}>{user.name[0]}</span>
              </div>
              <button className="btn-green" onClick={onLogout} style={{padding:"8px 16px", fontSize:13}}>Logout</button>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
}

// ── LANDING ─────────────────────────────────────────────────────────────────
function Landing({onNav}) {
  const [count, setCount] = useState({students:0, surahs:0, teachers:0});
  useEffect(()=>{
    const t = setTimeout(()=>setCount({students:1240, surahs:114, teachers:47}), 300);
    return ()=>clearTimeout(t);
  },[]);

  const features = [
    {icon:<BookOpen size={24} color="#D4AF37"/>, title:"Structured Hifz", desc:"Progressive curriculum covering all 114 surahs with tajweed guidance."},
    {icon:<Shield size={24} color="#D4AF37"/>, title:"Child-Safe Platform", desc:"COPPA compliant with parental consent flows and moderated content."},
    {icon:<Users size={24} color="#D4AF37"/>, title:"Dedicated Teachers", desc:"Personal teacher assignment with progress tracking and approvals."},
    {icon:<Award size={24} color="#D4AF37"/>, title:"Badges & Streaks", desc:"Gamified learning with achievement badges and daily streak tracking."},
    {icon:<Globe size={24} color="#D4AF37"/>, title:"Arabic & English", desc:"Fully bilingual platform supporting both RTL and LTR learners."},
    {icon:<TrendingUp size={24} color="#D4AF37"/>, title:"Progress Reports", desc:"Detailed weekly PDF reports for students, teachers, and parents."},
  ];

  return (
    <div>
      {/* HERO */}
      <div className="hero-bg" style={{display:"flex", flexDirection:"column", alignItems:"center", justifyContent:"center", textAlign:"center", padding:"80px 24px 100px"}}>
        <div className="animate-fade">
          <div className="badge badge-gold" style={{marginBottom:20, fontSize:13, padding:"6px 16px"}}>🕌 omarhesham.school</div>
          <div className="arabic" style={{fontSize:28, color:"#D4AF37", marginBottom:8, opacity:.9}}>بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</div>
          <h1 style={{fontSize:"clamp(36px,6vw,72px)", fontWeight:900, color:"#fff", lineHeight:1.1, marginBottom:20}}>
            Memorize the Quran<br/><span className="gold-gradient">with Purpose & Guidance</span>
          </h1>
          <p style={{color:"rgba(255,255,255,.7)", fontSize:18, maxWidth:560, margin:"0 auto 40px", lineHeight:1.7}}>
            A structured, teacher-guided platform for students of all ages to memorize, learn tajweed, and track their Hifz journey.
          </p>
          <div style={{display:"flex", gap:12, justifyContent:"center", flexWrap:"wrap"}}>
            <button className="btn-gold" onClick={()=>onNav("register")} style={{fontSize:16, padding:"14px 32px"}}>Start Free Today <ArrowRight size={16} style={{display:"inline", marginLeft:4}}/></button>
            <button className="btn-green" onClick={()=>onNav("programs")} style={{fontSize:16, padding:"14px 32px", border:"1px solid rgba(255,255,255,.2)"}}>View Programs</button>
          </div>
        </div>
        {/* Stats */}
        <div style={{display:"flex", gap:48, marginTop:72, flexWrap:"wrap", justifyContent:"center"}}>
          {[["Students",count.students,"enrolled"],["Surahs",count.surahs,"covered"],["Teachers",count.teachers,"active"]].map(([l,v,s])=>(
            <div key={l} style={{textAlign:"center"}}>
              <div style={{fontSize:42, fontWeight:900, color:"#D4AF37"}}>{v.toLocaleString()}+</div>
              <div style={{color:"rgba(255,255,255,.6)", fontSize:14}}>{l} {s}</div>
            </div>
          ))}
        </div>
      </div>

      {/* DAILY AYAH */}
      <div style={{background:"#0A1F14", padding:"48px 24px"}}>
        <div style={{maxWidth:700, margin:"0 auto"}}>
          <div className="ayah-card">
            <div style={{fontSize:11, letterSpacing:2, color:"#D4AF37", marginBottom:12, textTransform:"uppercase"}}>Ayah of the Day</div>
            <div className="arabic" style={{fontSize:28, lineHeight:1.8, marginBottom:16}}>{dailyAyah.ar}</div>
            <div style={{color:"rgba(255,255,255,.8)", fontSize:15, marginBottom:8}}>{dailyAyah.en}</div>
            <div style={{color:"#D4AF37", fontSize:13}}>— {dailyAyah.ref}</div>
          </div>
        </div>
      </div>

      {/* FEATURES */}
      <div style={{padding:"80px 24px", background:"#fff"}}>
        <div style={{maxWidth:1100, margin:"0 auto"}}>
          <div style={{textAlign:"center", marginBottom:56}}>
            <h2 style={{fontSize:36, fontWeight:800, color:"#0A1F14", marginBottom:12}}>Everything You Need to Succeed</h2>
            <p style={{color:"#6B7280", fontSize:16}}>A complete platform built specifically for Quran memorization schools.</p>
          </div>
          <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(300px,1fr))", gap:24}}>
            {features.map(f=>(
              <div key={f.title} className="card-hover" style={{background:"#FAFAF8", border:"1px solid #E5E7EB", borderRadius:16, padding:28}}>
                <div className="feature-icon" style={{marginBottom:16}}>{f.icon}</div>
                <h3 style={{fontWeight:700, fontSize:17, marginBottom:8, color:"#0A1F14"}}>{f.title}</h3>
                <p style={{color:"#6B7280", lineHeight:1.6, fontSize:14}}>{f.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* PROGRAMS PREVIEW */}
      <div style={{padding:"80px 24px", background:"#F9FAFB"}}>
        <div style={{maxWidth:1100, margin:"0 auto"}}>
          <div style={{textAlign:"center", marginBottom:48}}>
            <h2 style={{fontSize:36, fontWeight:800, color:"#0A1F14", marginBottom:12}}>Featured Programs</h2>
          </div>
          <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(300px,1fr))", gap:24}}>
            {programs.slice(0,3).map(p=>(
              <div key={p.id} className="card-hover" style={{background:"#fff", border:"1px solid #E5E7EB", borderRadius:16, overflow:"hidden"}}>
                <div className="green-gradient" style={{padding:"24px 24px 20px", display:"flex", justifyContent:"space-between", alignItems:"flex-start"}}>
                  <div>
                    <div className="arabic" style={{color:"rgba(255,255,255,.7)", fontSize:15, marginBottom:4}}>{p.titleAr}</div>
                    <div style={{color:"#D4AF37", fontSize:12, fontWeight:600}}>{p.juz}</div>
                  </div>
                  <span className={`badge ${p.level==="free"?"badge-green":"badge-gold"}`}>{p.level}</span>
                </div>
                <div style={{padding:24}}>
                  <h3 style={{fontWeight:700, fontSize:16, marginBottom:8}}>{p.title}</h3>
                  <p style={{color:"#6B7280", fontSize:13, lineHeight:1.6, marginBottom:16}}>{p.desc}</p>
                  <div style={{display:"flex", justifyContent:"space-between", alignItems:"center"}}>
                    <span style={{color:"#6B7280", fontSize:13}}><Users size={13} style={{display:"inline", marginRight:4}}/>{p.students} students</span>
                    <button className="btn-green" onClick={()=>onNav("register")} style={{padding:"8px 16px", fontSize:13}}>Enroll Free</button>
                  </div>
                </div>
              </div>
            ))}
          </div>
          <div style={{textAlign:"center", marginTop:32}}>
            <button className="btn-green" onClick={()=>onNav("programs")} style={{fontSize:15}}>View All Programs →</button>
          </div>
        </div>
      </div>

      {/* CTA */}
      <div className="green-gradient" style={{padding:"80px 24px", textAlign:"center"}}>
        <div style={{maxWidth:600, margin:"0 auto"}}>
          <h2 style={{color:"#fff", fontSize:36, fontWeight:800, marginBottom:16}}>Begin Your Hifz Journey Today</h2>
          <p style={{color:"rgba(255,255,255,.75)", fontSize:16, marginBottom:32, lineHeight:1.7}}>Join thousands of students memorizing the Quran with qualified teachers on omarhesham.school</p>
          <button className="btn-gold" onClick={()=>onNav("register")} style={{fontSize:16, padding:"14px 40px"}}>Create Free Account</button>
        </div>
      </div>

      {/* FOOTER */}
      <footer style={{background:"#0A1F14", color:"rgba(255,255,255,.6)", padding:"48px 24px", textAlign:"center"}}>
        <div style={{display:"flex", justifyContent:"center", gap:10, alignItems:"center", marginBottom:16}}>
          <BookOpen size={20} color="#D4AF37"/>
          <span style={{color:"#fff", fontWeight:700}}>omarhesham.school</span>
        </div>
        <div style={{display:"flex", gap:24, justifyContent:"center", flexWrap:"wrap", marginBottom:16, fontSize:14}}>
          {["Privacy Policy","Terms of Service","Child Safety","Cookies"].map(l=>(
            <span key={l} style={{cursor:"pointer", color:"rgba(255,255,255,.5)"}}>{l}</span>
          ))}
        </div>
        <div style={{fontSize:13}}>© 2026 omarhesham.school — All rights reserved.</div>
      </footer>
    </div>
  );
}

// ── AUTH ─────────────────────────────────────────────────────────────────────
function AuthPage({type, onNav, onLogin}) {
  const [form, setForm] = useState({name:"", email:"", password:"", role:"student", age_group:"adult"});
  const [loading, setLoading] = useState(false);
  const [err, setErr] = useState("");
  const isLogin = type==="login";

  const handle = (e) => {
    e.preventDefault(); setErr(""); setLoading(true);
    setTimeout(()=>{
      if(isLogin && form.email && form.password) {
        const roles = {admin:{role:"admin",name:"Admin"}, teacher:{role:"teacher",name:"Fatima Hassan"}, student:{role:"student",name:"Ahmed Al-Rashid"}};
        const role = form.email.includes("admin")?"admin":form.email.includes("teacher")?"teacher":"student";
        onLogin({...roles[role], email:form.email, age_group:"adult"});
      } else if(!isLogin && form.name && form.email && form.password) {
        onLogin({name:form.name, email:form.email, role:form.role, age_group:form.age_group});
      } else { setErr("Please fill all required fields."); }
      setLoading(false);
    }, 900);
  };

  return (
    <div style={{minHeight:"100vh", display:"flex", alignItems:"center", justifyContent:"center", padding:24, background:"linear-gradient(135deg,#0A1F14,#1B4332)"}}>
      <div style={{background:"#fff", borderRadius:24, padding:40, width:"100%", maxWidth:440, boxShadow:"0 40px 80px rgba(0,0,0,.3)"}}>
        <div style={{textAlign:"center", marginBottom:32}}>
          <div style={{width:52, height:52, borderRadius:14, background:"linear-gradient(135deg,#D4AF37,#F0C040)", display:"flex", alignItems:"center", justifyContent:"center", margin:"0 auto 16px"}}>
            <BookOpen size={26} color="#0A1F14"/>
          </div>
          <h1 style={{fontWeight:800, fontSize:24, color:"#0A1F14"}}>{isLogin?"Welcome Back":"Create Account"}</h1>
          <p style={{color:"#6B7280", fontSize:14, marginTop:6}}>omarhesham.school</p>
        </div>

        {err && <div style={{background:"#FEE2E2", color:"#991B1B", padding:"10px 14px", borderRadius:8, marginBottom:16, fontSize:14}}>{err}</div>}

        <div>
          {!isLogin && (
            <div style={{marginBottom:16}}>
              <label style={{display:"block", fontSize:13, fontWeight:600, color:"#374151", marginBottom:6}}>Full Name</label>
              <input className="input-field" placeholder="Your full name" value={form.name} onChange={e=>setForm({...form,name:e.target.value})}/>
            </div>
          )}
          <div style={{marginBottom:16}}>
            <label style={{display:"block", fontSize:13, fontWeight:600, color:"#374151", marginBottom:6}}>Email Address</label>
            <input className="input-field" type="email" placeholder="you@example.com" value={form.email} onChange={e=>setForm({...form,email:e.target.value})}/>
          </div>
          <div style={{marginBottom:16}}>
            <label style={{display:"block", fontSize:13, fontWeight:600, color:"#374151", marginBottom:6}}>Password</label>
            <input className="input-field" type="password" placeholder="••••••••" value={form.password} onChange={e=>setForm({...form,password:e.target.value})}/>
          </div>
          {!isLogin && (
            <>
              <div style={{marginBottom:16}}>
                <label style={{display:"block", fontSize:13, fontWeight:600, color:"#374151", marginBottom:6}}>I am a</label>
                <select className="input-field" value={form.role} onChange={e=>setForm({...form,role:e.target.value})}>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                </select>
              </div>
              <div style={{marginBottom:16}}>
                <label style={{display:"block", fontSize:13, fontWeight:600, color:"#374151", marginBottom:6}}>Age Group</label>
                <select className="input-field" value={form.age_group} onChange={e=>setForm({...form,age_group:e.target.value})}>
                  <option value="adult">Adult (18+)</option>
                  <option value="child">Child / Minor (under 18)</option>
                </select>
              </div>
              {form.age_group==="child" && (
                <div style={{background:"#FEF9C3", border:"1px solid #FDE047", borderRadius:10, padding:12, marginBottom:16, fontSize:13, color:"#854D0E"}}>
                  ⚠️ A parent consent email will be sent to your guardian's email address for COPPA compliance.
                </div>
              )}
            </>
          )}
          <button className="btn-gold" style={{width:"100%", marginTop:8}} onClick={handle} disabled={loading}>
            {loading ? "Please wait..." : isLogin?"Sign In":"Create Account"}
          </button>
        </div>
        <div style={{textAlign:"center", marginTop:20, fontSize:14, color:"#6B7280"}}>
          {isLogin ? <>New? <span style={{color:"#1B4332", fontWeight:600, cursor:"pointer"}} onClick={()=>onNav("register")}>Create account</span></> : <>Have an account? <span style={{color:"#1B4332", fontWeight:600, cursor:"pointer"}} onClick={()=>onNav("login")}>Sign in</span></>}
        </div>
        {isLogin && (
          <div style={{textAlign:"center", marginTop:12, fontSize:13, color:"#9CA3AF"}}>
            Try: admin@school.com / teacher@school.com / any@email.com
          </div>
        )}
      </div>
    </div>
  );
}

// ── PROGRAMS PAGE ─────────────────────────────────────────────────────────────
function ProgramsPage({onNav, user}) {
  const [filter, setFilter] = useState("all");
  const filtered = programs.filter(p => filter==="all" || p.level===filter);
  return (
    <div style={{maxWidth:1100, margin:"0 auto", padding:"48px 24px"}}>
      <div style={{marginBottom:40}}>
        <h1 style={{fontSize:36, fontWeight:800, color:"#0A1F14", marginBottom:8}}>All Programs</h1>
        <p style={{color:"#6B7280"}}>Choose a program that fits your level and goals.</p>
        <div style={{display:"flex", gap:8, marginTop:20}}>
          {["all","free","premium"].map(f=>(
            <button key={f} onClick={()=>setFilter(f)} style={{padding:"8px 20px", borderRadius:20, border:"2px solid", borderColor:filter===f?"#1B4332":"#E5E7EB", background:filter===f?"#1B4332":"#fff", color:filter===f?"#fff":"#374151", cursor:"pointer", fontWeight:600, fontSize:13, textTransform:"capitalize"}}>{f}</button>
          ))}
        </div>
      </div>
      <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fill,minmax(320px,1fr))", gap:24}}>
        {filtered.map(p=>(
          <div key={p.id} className="card-hover" style={{background:"#fff", border:"1px solid #E5E7EB", borderRadius:16, overflow:"hidden"}}>
            <div className="green-gradient" style={{padding:"24px"}}>
              <div style={{display:"flex", justifyContent:"space-between"}}>
                <div className="arabic" style={{color:"rgba(255,255,255,.8)", fontSize:16}}>{p.titleAr}</div>
                <span className={`badge ${p.level==="free"?"badge-green":"badge-gold"}`}>{p.level}</span>
              </div>
              <div style={{color:"#D4AF37", fontSize:13, marginTop:6, fontWeight:600}}>{p.juz}</div>
            </div>
            <div style={{padding:24}}>
              <h3 style={{fontWeight:700, fontSize:17, marginBottom:8, color:"#0A1F14"}}>{p.title}</h3>
              <p style={{color:"#6B7280", fontSize:13, lineHeight:1.6, marginBottom:16}}>{p.desc}</p>
              <div style={{display:"flex", justifyContent:"space-between", alignItems:"center"}}>
                <span style={{color:"#6B7280", fontSize:13}}><Users size={13} style={{display:"inline", marginRight:4}}/>{p.students} students</span>
                {p.level==="premium" && !user?.subscribed ? (
                  <button className="btn-gold" onClick={()=>onNav("pricing")} style={{padding:"8px 16px", fontSize:13}}>Subscribe</button>
                ) : (
                  <button className="btn-green" onClick={()=>user?onNav("dashboard"):onNav("register")} style={{padding:"8px 16px", fontSize:13}}>Enroll</button>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

// ── PRICING PAGE ─────────────────────────────────────────────────────────────
function PricingPage({onNav}) {
  return (
    <div style={{padding:"72px 24px", background:"#F9FAFB", minHeight:"100vh"}}>
      <div style={{maxWidth:900, margin:"0 auto", textAlign:"center"}}>
        <h1 style={{fontSize:40, fontWeight:900, color:"#0A1F14", marginBottom:12}}>Simple, Transparent Pricing</h1>
        <p style={{color:"#6B7280", fontSize:16, marginBottom:56}}>All free programs are always free. Premium unlocks advanced content.</p>
        <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(280px,1fr))", gap:24, maxWidth:700, margin:"0 auto"}}>
          {[
            {name:"Free", price:"$0", period:"forever", color:"#1B4332", features:["All free programs","Basic progress tracking","Community forums","Mobile access"], cta:"Get Started", action:"register"},
            {name:"Premium", price:"$12", period:"per month", color:"#D4AF37", features:["All programs (including advanced)","Dedicated teacher assignment","Weekly PDF reports","Priority support","Completion certificates"], cta:"Subscribe Now", action:"register", featured:true},
          ].map(plan=>(
            <div key={plan.name} className="card-hover" style={{background:"#fff", border:`2px solid ${plan.featured?"#D4AF37":"#E5E7EB"}`, borderRadius:20, padding:36, position:"relative"}}>
              {plan.featured && <div style={{position:"absolute", top:-14, left:"50%", transform:"translateX(-50%)", background:"linear-gradient(135deg,#D4AF37,#F0C040)", color:"#0A1F14", padding:"4px 20px", borderRadius:20, fontSize:12, fontWeight:700}}>MOST POPULAR</div>}
              <h2 style={{fontSize:24, fontWeight:800, color:"#0A1F14", marginBottom:4}}>{plan.name}</h2>
              <div style={{fontSize:42, fontWeight:900, color:plan.color, marginBottom:4}}>{plan.price}</div>
              <div style={{color:"#9CA3AF", fontSize:13, marginBottom:24}}>{plan.period}</div>
              {plan.features.map(f=>(
                <div key={f} style={{display:"flex", gap:8, alignItems:"center", marginBottom:12, textAlign:"left"}}>
                  <CheckCircle size={16} color="#1B4332"/><span style={{fontSize:14, color:"#374151"}}>{f}</span>
                </div>
              ))}
              <button className={plan.featured?"btn-gold":"btn-green"} style={{width:"100%", marginTop:16}} onClick={()=>onNav(plan.action)}>{plan.cta}</button>
            </div>
          ))}
        </div>
        <p style={{color:"#9CA3AF", fontSize:13, marginTop:32}}>Annual plan available at $99/year (save 31%). Cancel anytime.</p>
      </div>
    </div>
  );
}

// ── DONATE PAGE ──────────────────────────────────────────────────────────────
function DonatePage({user}) {
  const [amount, setAmount] = useState(25);
  const [type, setType] = useState("one_time");
  const [donated, setDonated] = useState(false);
  const presets = [5,10,25,50,100];
  return (
    <div style={{padding:"72px 24px", background:"linear-gradient(160deg,#0A1F14,#1B4332)", minHeight:"100vh", display:"flex", alignItems:"center", justifyContent:"center"}}>
      <div style={{maxWidth:520, width:"100%"}}>
        {donated ? (
          <div style={{background:"#fff", borderRadius:24, padding:48, textAlign:"center"}}>
            <div style={{fontSize:48, marginBottom:16}}>🤲</div>
            <h2 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:8}}>JazakAllahu Khayran!</h2>
            <p style={{color:"#6B7280", fontSize:16}}>Your ${amount} donation will help more students memorize the Quran. May Allah accept it from you.</p>
          </div>
        ) : (
          <div style={{background:"#fff", borderRadius:24, padding:40}}>
            <div style={{textAlign:"center", marginBottom:32}}>
              <div style={{fontSize:40, marginBottom:8}}>🕌</div>
              <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:8}}>Support Hifz Education</h1>
              <p style={{color:"#6B7280", fontSize:14}}>Your sadaqah helps provide scholarships and free access.</p>
            </div>
            <div style={{marginBottom:20}}>
              <div style={{display:"flex", gap:8, flexWrap:"wrap", marginBottom:12}}>
                {presets.map(p=>(
                  <button key={p} onClick={()=>setAmount(p)} style={{flex:1, minWidth:60, padding:"10px 4px", border:`2px solid ${amount===p?"#1B4332":"#E5E7EB"}`, borderRadius:10, background:amount===p?"#1B4332":"#fff", color:amount===p?"#fff":"#374151", fontWeight:700, cursor:"pointer"}}>${p}</button>
                ))}
              </div>
              <input className="input-field" type="number" placeholder="Custom amount ($)" value={amount} onChange={e=>setAmount(Number(e.target.value))} min="1" max="10000"/>
            </div>
            <div style={{display:"flex", gap:8, marginBottom:24}}>
              {[["one_time","One-Time"],["recurring","Monthly"]].map(([v,l])=>(
                <button key={v} onClick={()=>setType(v)} style={{flex:1, padding:"10px", border:`2px solid ${type===v?"#1B4332":"#E5E7EB"}`, borderRadius:10, background:type===v?"#1B4332":"#fff", color:type===v?"#fff":"#374151", fontWeight:600, cursor:"pointer", fontSize:14}}>{l}</button>
              ))}
            </div>
            <button className="btn-gold" style={{width:"100%", fontSize:16}} onClick={()=>setDonated(true)}>
              Donate ${amount} {type==="recurring"?"Monthly":"Now"} <Heart size={16} style={{display:"inline", marginLeft:4}}/>
            </button>
            <p style={{textAlign:"center", color:"#9CA3AF", fontSize:12, marginTop:12}}>Secure payment via Stripe. Tax deductible where applicable.</p>
          </div>
        )}
      </div>
    </div>
  );
}

// ── DASHBOARD SHELL ──────────────────────────────────────────────────────────
function DashboardShell({user, onNav, onLogout}) {
  const [activeTab, setActiveTab] = useState("overview");
  const isAdmin = user?.role==="admin"||user?.role==="center_admin";
  const isTeacher = user?.role==="teacher";

  const studentLinks = [{id:"overview",label:"Dashboard",icon:<Home size={16}/>},{id:"progress",label:"My Progress",icon:<BarChart2 size={16}/>},{id:"programs",label:"Programs",icon:<BookOpen size={16}/>},{id:"settings",label:"Settings",icon:<Settings size={16}/>}];
  const teacherLinks = [{id:"overview",label:"Dashboard",icon:<Home size={16}/>},{id:"students",label:"My Students",icon:<Users size={16}/>},{id:"upload",label:"Upload Content",icon:<Upload size={16}/>},{id:"settings",label:"Settings",icon:<Settings size={16}/>}];
  const adminLinks = [{id:"overview",label:"Dashboard",icon:<Home size={16}/>},{id:"users",label:"Users",icon:<Users size={16}/>},{id:"moderation",label:"Moderation",icon:<Shield size={16}/>},{id:"programs_admin",label:"Programs",icon:<BookOpen size={16}/>},{id:"reports",label:"Reports",icon:<BarChart2 size={16}/>},{id:"settings",label:"Settings",icon:<Settings size={16}/>}];

  const links = isAdmin ? adminLinks : isTeacher ? teacherLinks : studentLinks;

  const renderContent = () => {
    if(activeTab==="overview") return isAdmin ? <AdminOverview/> : isTeacher ? <TeacherOverview setTab={setActiveTab}/> : <StudentOverview setTab={setActiveTab}/>;
    if(activeTab==="progress") return <StudentProgress/>;
    if(activeTab==="programs") return <ProgramsPage onNav={onNav} user={user}/>;
    if(activeTab==="students") return <TeacherStudents/>;
    if(activeTab==="upload") return <TeacherUpload/>;
    if(activeTab==="users") return <AdminUsers/>;
    if(activeTab==="moderation") return <AdminModeration/>;
    if(activeTab==="programs_admin") return <AdminPrograms/>;
    if(activeTab==="reports") return <AdminReports/>;
    if(activeTab==="settings") return <AccountSettings user={user}/>;
    return null;
  };

  return (
    <div style={{display:"flex", minHeight:"calc(100vh - 68px)"}}>
      {/* SIDEBAR */}
      <div style={{width:240, background:"#fff", borderRight:"1px solid #E5E7EB", padding:"24px 12px", flexShrink:0}}>
        <div style={{marginBottom:24, padding:"0 8px"}}>
          <div style={{fontWeight:700, fontSize:15, color:"#0A1F14"}}>{user?.name}</div>
          <div style={{fontSize:12, color:"#9CA3AF", textTransform:"capitalize"}}>{user?.role?.replace("_"," ")}</div>
          {user?.role==="student" && <div style={{marginTop:8}}><div className="badge badge-green">🔥 7 day streak</div></div>}
        </div>
        <div>
          {links.map(l=>(
            <div key={l.id} className={`sidebar-link ${activeTab===l.id?"active":""}`} onClick={()=>setActiveTab(l.id)}>
              {l.icon}<span>{l.label}</span>
            </div>
          ))}
          <div className="sidebar-link" onClick={onLogout} style={{marginTop:8, color:"#EF4444"}}>
            <LogOut size={16}/><span>Logout</span>
          </div>
        </div>
      </div>
      {/* MAIN */}
      <div style={{flex:1, overflow:"auto", background:"#F9FAFB"}}>
        {renderContent()}
      </div>
    </div>
  );
}

// ── STUDENT OVERVIEW ──────────────────────────────────────────────────────────
function StudentOverview({setTab}) {
  const [showLogModal, setShowLogModal] = useState(false);
  const [logForm, setLogForm] = useState({surah:"1",ayah_from:"1",ayah_to:"7",quality:"5",notes:""});
  const [logs, setLogs] = useState(recentLogs);
  const [toastMsg, setToastMsg] = useState("");

  const saveLog = () => {
    const newLog = {id:logs.length+1, surah:`Surah ${logForm.surah}`, ayah:`${logForm.ayah_from}–${logForm.ayah_to}`, quality:parseInt(logForm.quality), date:new Date().toISOString().split("T")[0], status:"pending"};
    setLogs([newLog,...logs]);
    setShowLogModal(false);
    setToastMsg("✅ Progress logged successfully!");
    setTimeout(()=>setToastMsg(""),3000);
  };

  return (
    <div style={{padding:32}} className="animate-fade">
      {toastMsg && <div style={{position:"fixed", top:90, right:24, background:"#1B4332", color:"#fff", padding:"12px 20px", borderRadius:12, zIndex:200, fontWeight:600}}>{toastMsg}</div>}
      <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:32}}>
        <div>
          <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14"}}>Student Dashboard</h1>
          <p style={{color:"#6B7280", fontSize:14, marginTop:4}}>Thursday, February 26, 2026</p>
        </div>
        <button className="btn-gold" onClick={()=>setShowLogModal(true)}>+ Log Progress</button>
      </div>

      {/* DAILY AYAH */}
      <div className="ayah-card" style={{marginBottom:24}}>
        <div style={{fontSize:10, letterSpacing:2, color:"rgba(212,175,55,.8)", marginBottom:8}}>TODAY'S AYAH</div>
        <div className="arabic" style={{fontSize:22, lineHeight:1.8}}>{dailyAyah.ar}</div>
        <div style={{color:"rgba(255,255,255,.75)", fontSize:13, marginTop:8}}>{dailyAyah.en}</div>
        <div style={{color:"#D4AF37", fontSize:12, marginTop:4}}>— {dailyAyah.ref}</div>
      </div>

      {/* STATS */}
      <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(160px,1fr))", gap:16, marginBottom:24}}>
        {[["🔥","7","Day Streak"],["📖","43","Ayahs This Week"],["✅","128","Total Logged"],["🏅","5","Badges Earned"]].map(([e,v,l])=>(
          <div key={l} className="stat-card" style={{textAlign:"center"}}>
            <div style={{fontSize:28, marginBottom:4}}>{e}</div>
            <div style={{fontSize:32, fontWeight:800, color:"#1B4332"}}>{v}</div>
            <div style={{fontSize:12, color:"#9CA3AF"}}>{l}</div>
          </div>
        ))}
      </div>

      {/* WEEKLY PROGRESS */}
      <div className="stat-card" style={{marginBottom:24}}>
        <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>Weekly Progress</h3>
        {[["Mon","12",80],["Tue","8",55],["Wed","15",100],["Thu","10",67],["Fri","7",47],["Sat","0",0],["Sun","1",7]].map(([d,n,p])=>(
          <div key={d} style={{display:"flex", alignItems:"center", gap:12, marginBottom:8}}>
            <div style={{width:32, fontSize:12, color:"#6B7280"}}>{d}</div>
            <div style={{flex:1}}><div className="progress-bar"><div className="progress-fill" style={{width:`${p}%`}}/></div></div>
            <div style={{width:24, fontSize:12, fontWeight:600, color:"#1B4332"}}>{n}</div>
          </div>
        ))}
      </div>

      {/* RECENT LOGS */}
      <div className="stat-card">
        <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:16}}>
          <h3 style={{fontWeight:700, color:"#0A1F14"}}>Recent Progress Logs</h3>
          <span style={{fontSize:13, color:"#1B4332", cursor:"pointer", fontWeight:600}} onClick={()=>setTab("progress")}>View All →</span>
        </div>
        <table className="table">
          <thead><tr><th>Surah</th><th>Ayahs</th><th>Quality</th><th>Date</th><th>Status</th></tr></thead>
          <tbody>{logs.slice(0,5).map(l=>(
            <tr key={l.id}>
              <td style={{fontWeight:600}}>{l.surah}</td>
              <td style={{color:"#6B7280"}}>{l.ayah}</td>
              <td><Stars n={l.quality}/></td>
              <td style={{color:"#6B7280", fontSize:13}}>{l.date}</td>
              <td><span className={`badge ${l.status==="approved"?"badge-green":"badge-gold"}`}>{l.status}</span></td>
            </tr>
          ))}</tbody>
        </table>
      </div>

      {/* LOG MODAL */}
      {showLogModal && (
        <div className="modal-overlay" onClick={()=>setShowLogModal(false)}>
          <div className="modal" onClick={e=>e.stopPropagation()}>
            <h2 style={{fontWeight:800, fontSize:20, marginBottom:24, color:"#0A1F14"}}>Log Progress</h2>
            <div style={{marginBottom:14}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Surah Number (1–114)</label>
              <input className="input-field" type="number" min="1" max="114" value={logForm.surah} onChange={e=>setLogForm({...logForm,surah:e.target.value})}/>
            </div>
            <div style={{display:"grid", gridTemplateColumns:"1fr 1fr", gap:12, marginBottom:14}}>
              <div>
                <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Ayah From</label>
                <input className="input-field" type="number" min="1" value={logForm.ayah_from} onChange={e=>setLogForm({...logForm,ayah_from:e.target.value})}/>
              </div>
              <div>
                <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Ayah To</label>
                <input className="input-field" type="number" min="1" value={logForm.ayah_to} onChange={e=>setLogForm({...logForm,ayah_to:e.target.value})}/>
              </div>
            </div>
            <div style={{marginBottom:14}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Quality (1–5)</label>
              <select className="input-field" value={logForm.quality} onChange={e=>setLogForm({...logForm,quality:e.target.value})}>
                {[5,4,3,2,1].map(n=><option key={n} value={n}>{"★".repeat(n)}{"☆".repeat(5-n)} — {["","Poor","Fair","Good","Very Good","Excellent"][n]}</option>)}
              </select>
            </div>
            <div style={{marginBottom:20}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Notes (optional)</label>
              <textarea className="input-field" style={{resize:"vertical", minHeight:60}} placeholder="Any notes about this session..." value={logForm.notes} onChange={e=>setLogForm({...logForm,notes:e.target.value})}/>
            </div>
            <div style={{display:"flex", gap:10}}>
              <button className="btn-gold" style={{flex:1}} onClick={saveLog}>Save Log</button>
              <button className="btn-green" style={{flex:1}} onClick={()=>setShowLogModal(false)}>Cancel</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── STUDENT PROGRESS ──────────────────────────────────────────────────────────
function StudentProgress() {
  return (
    <div style={{padding:32}} className="animate-fade">
      <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:32}}>
        <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14"}}>My Progress</h1>
        <button className="btn-gold">📄 Download PDF Report</button>
      </div>
      <div className="stat-card">
        <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>All Progress Logs</h3>
        <table className="table">
          <thead><tr><th>Surah</th><th>Ayahs</th><th>Quality</th><th>Date</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>{recentLogs.map(l=>(
            <tr key={l.id}>
              <td style={{fontWeight:600}}>{l.surah}</td>
              <td style={{color:"#6B7280"}}>{l.ayah}</td>
              <td><Stars n={l.quality}/></td>
              <td style={{color:"#6B7280", fontSize:13}}>{l.date}</td>
              <td><span className={`badge ${l.status==="approved"?"badge-green":"badge-gold"}`}>{l.status}</span></td>
              <td>{l.status==="pending"&&<Trash2 size={14} color="#EF4444" style={{cursor:"pointer"}}/>}</td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}

// ── TEACHER OVERVIEW ──────────────────────────────────────────────────────────
function TeacherOverview({setTab}) {
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Teacher Dashboard</h1>
      <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(160px,1fr))", gap:16, marginBottom:24}}>
        {[["👥","12","Students"],["⏳","8","Pending Logs"],["📤","3","Content Pending"],["✅","47","Approved Logs"]].map(([e,v,l])=>(
          <div key={l} className="stat-card" style={{textAlign:"center"}}>
            <div style={{fontSize:28, marginBottom:4}}>{e}</div>
            <div style={{fontSize:32, fontWeight:800, color:"#1B4332"}}>{v}</div>
            <div style={{fontSize:12, color:"#9CA3AF"}}>{l}</div>
          </div>
        ))}
      </div>
      <div className="stat-card" style={{marginBottom:24}}>
        <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:16}}>
          <h3 style={{fontWeight:700, color:"#0A1F14"}}>Pending Approvals</h3>
          <span style={{fontSize:13, color:"#1B4332", cursor:"pointer", fontWeight:600}} onClick={()=>setTab("students")}>View All →</span>
        </div>
        <table className="table">
          <thead><tr><th>Student</th><th>Surah</th><th>Quality</th><th>Date</th><th>Actions</th></tr></thead>
          <tbody>
            {[["Ahmed Al-Rashid","Al-Baqarah 1–10",4,"2026-02-25"],["Layla Omar","Al-Fatiha 1–7",5,"2026-02-25"],["Mohammed K.","Al-Baqarah 11–20",3,"2026-02-24"]].map(([s,q,r,d])=>(
              <tr key={s}><td style={{fontWeight:600}}>{s}</td><td style={{color:"#6B7280"}}>{q}</td><td><Stars n={r}/></td><td style={{color:"#6B7280",fontSize:13}}>{d}</td>
              <td style={{display:"flex", gap:8}}><button className="btn-green" style={{padding:"6px 12px",fontSize:12}}>✓ Approve</button><button style={{padding:"6px 12px",fontSize:12,borderRadius:8,border:"1px solid #EF4444",color:"#EF4444",background:"none",cursor:"pointer"}}>✗ Reject</button></td></tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

// ── TEACHER STUDENTS ──────────────────────────────────────────────────────────
function TeacherStudents() {
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>My Students</h1>
      <div className="stat-card">
        <table className="table">
          <thead><tr><th>Student</th><th>Age Group</th><th>This Week</th><th>Streak</th><th>Actions</th></tr></thead>
          <tbody>
            {[["Ahmed Al-Rashid","adult","43 ayahs","7 days"],["Layla Omar","child","28 ayahs","3 days"],["Mohammed K.","adult","15 ayahs","1 day"],["Sara Hassan","child","50 ayahs","12 days"]].map(([n,a,w,s])=>(
              <tr key={n}>
                <td><div style={{fontWeight:600}}>{n}</div></td>
                <td><span className={`badge ${a==="child"?"badge-gold":"badge-blue"}`}>{a}</span></td>
                <td style={{color:"#1B4332", fontWeight:600}}>{w}</td>
                <td>🔥 {s}</td>
                <td style={{display:"flex", gap:8}}><button className="btn-green" style={{padding:"6px 14px",fontSize:12}}>View Progress</button><button style={{padding:"6px 14px",fontSize:12,background:"#FEE2E2",color:"#991B1B",border:"none",borderRadius:8,cursor:"pointer"}}>Remove</button></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}

// ── TEACHER UPLOAD ────────────────────────────────────────────────────────────
function TeacherUpload() {
  const [tab, setTab] = useState("file");
  const [uploaded, setUploaded] = useState(false);
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Upload Content</h1>
      <div className="stat-card" style={{maxWidth:560}}>
        <div style={{display:"flex", gap:8, marginBottom:24}}>
          {[["file","📁 Upload File"],["youtube","▶ YouTube"]].map(([v,l])=>(
            <button key={v} onClick={()=>setTab(v)} style={{flex:1, padding:"10px", border:`2px solid ${tab===v?"#1B4332":"#E5E7EB"}`, borderRadius:10, background:tab===v?"#1B4332":"#fff", color:tab===v?"#fff":"#374151", fontWeight:600, cursor:"pointer"}}>{l}</button>
          ))}
        </div>
        {uploaded ? (
          <div style={{textAlign:"center", padding:32}}>
            <div style={{fontSize:40, marginBottom:12}}>✅</div>
            <h3 style={{fontWeight:700, color:"#1B4332", marginBottom:8}}>Uploaded Successfully</h3>
            <p style={{color:"#6B7280", fontSize:14}}>Your content is now pending admin review. It will be visible to students once approved.</p>
            <button className="btn-green" style={{marginTop:16}} onClick={()=>setUploaded(false)}>Upload Another</button>
          </div>
        ) : tab==="file" ? (
          <>
            <div style={{marginBottom:14}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Content Title</label>
              <input className="input-field" placeholder="e.g. Tajweed Rules — Lesson 1"/>
            </div>
            <div style={{marginBottom:14}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>File Type</label>
              <select className="input-field"><option>PDF</option><option>Audio (MP3/WAV)</option><option>Image</option></select>
            </div>
            <div style={{marginBottom:20, border:"2px dashed #D1D5DB", borderRadius:12, padding:32, textAlign:"center", cursor:"pointer", background:"#FAFAF8"}} onClick={()=>setUploaded(true)}>
              <Upload size={28} color="#9CA3AF" style={{margin:"0 auto 8px"}}/>
              <div style={{color:"#6B7280", fontSize:14}}>Click to select file or drag & drop</div>
              <div style={{color:"#9CA3AF", fontSize:12, marginTop:4}}>Max 20MB · PDF, MP3, MP4, JPG, PNG</div>
            </div>
            <button className="btn-gold" style={{width:"100%"}} onClick={()=>setUploaded(true)}>Upload Content</button>
          </>
        ) : (
          <>
            <div style={{marginBottom:14}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>Video Title</label>
              <input className="input-field" placeholder="e.g. Makhaarij Al-Huruf Explained"/>
            </div>
            <div style={{marginBottom:20}}>
              <label style={{fontSize:13, fontWeight:600, display:"block", marginBottom:6, color:"#374151"}}>YouTube URL</label>
              <input className="input-field" placeholder="https://youtube.com/watch?v=..."/>
            </div>
            <div style={{background:"#FEF9C3", borderRadius:10, padding:12, marginBottom:16, fontSize:13, color:"#854D0E"}}>⚠️ All YouTube videos are reviewed by admin before appearing to students.</div>
            <button className="btn-gold" style={{width:"100%"}} onClick={()=>setUploaded(true)}>Add YouTube Video</button>
          </>
        )}
      </div>

      {/* Existing uploads */}
      <div className="stat-card" style={{marginTop:24}}>
        <h3 style={{fontWeight:700, color:"#0A1F14", marginBottom:16}}>Your Uploads</h3>
        <table className="table">
          <thead><tr><th>Title</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
          <tbody>{contentItems.map(i=>(
            <tr key={i.id}>
              <td style={{fontWeight:600}}>{i.title}</td>
              <td><span className="badge badge-blue" style={{display:"inline-flex", alignItems:"center", gap:4}}><TypeIcon type={i.type}/>{i.type}</span></td>
              <td><span className={`badge ${i.status==="approved"?"badge-green":"badge-gold"}`}>{i.status}</span></td>
              <td style={{fontSize:13, color:"#6B7280"}}>{i.date}</td>
              <td>{i.status!=="approved"&&<Trash2 size={14} color="#EF4444" style={{cursor:"pointer"}}/>}</td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}

// ── ADMIN OVERVIEW ────────────────────────────────────────────────────────────
function AdminOverview() {
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Admin Dashboard</h1>
      <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(160px,1fr))", gap:16, marginBottom:24}}>
        {[["👥","1,240","Total Users"],["👶","312","Children"],["⏳","8","Pending Consents"],["📤","4","Pending Content"],["💰","$4,820","Donations"],["📊","847","Logs / Week"]].map(([e,v,l])=>(
          <div key={l} className="stat-card" style={{textAlign:"center"}}>
            <div style={{fontSize:24, marginBottom:4}}>{e}</div>
            <div style={{fontSize:26, fontWeight:800, color:"#1B4332"}}>{v}</div>
            <div style={{fontSize:11, color:"#9CA3AF"}}>{l}</div>
          </div>
        ))}
      </div>
      <div style={{display:"grid", gridTemplateColumns:"1fr 1fr", gap:24}}>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>Recent Audit Log</h3>
          {[["user_banned","Admin","2min ago","badge-red"],["content_approved","Layla Ibrahim","5min ago","badge-green"],["password_reset","Ahmed Al-Rashid","1hr ago","badge-blue"],["donation_created","Fatima Hassan","2hr ago","badge-gold"],["student_assigned","Omar Teacher","3hr ago","badge-green"]].map(([a,u,t,b])=>(
            <div key={a+t} style={{display:"flex", justifyContent:"space-between", alignItems:"center", padding:"10px 0", borderBottom:"1px solid #F3F4F6"}}>
              <div><span className={`badge ${b}`} style={{marginRight:8}}>{a.replace(/_/g," ")}</span><span style={{fontSize:13, color:"#6B7280"}}>{u}</span></div>
              <span style={{fontSize:12, color:"#9CA3AF"}}>{t}</span>
            </div>
          ))}
        </div>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>Pending Consents</h3>
          {[["Yusuf Ibrahim","yusuf@example.com","2026-02-24"],["Amira Hassan","amira@example.com","2026-02-23"],["Khalid M.","khalid@example.com","2026-02-22"]].map(([n,e,d])=>(
            <div key={n} style={{display:"flex", justifyContent:"space-between", alignItems:"center", padding:"10px 0", borderBottom:"1px solid #F3F4F6"}}>
              <div><div style={{fontWeight:600, fontSize:14}}>{n}</div><div style={{fontSize:12, color:"#9CA3AF"}}>{e} · {d}</div></div>
              <button className="btn-gold" style={{padding:"5px 12px", fontSize:12}}>Resend</button>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

// ── ADMIN USERS ───────────────────────────────────────────────────────────────
function AdminUsers() {
  const [users, setUsers] = useState(adminUsers);
  const [search, setSearch] = useState("");
  const filtered = users.filter(u=>u.name.toLowerCase().includes(search.toLowerCase())||u.email.toLowerCase().includes(search.toLowerCase()));
  return (
    <div style={{padding:32}} className="animate-fade">
      <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:24}}>
        <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14"}}>Users</h1>
        <div style={{position:"relative"}}>
          <Search size={16} style={{position:"absolute", left:12, top:"50%", transform:"translateY(-50%)", color:"#9CA3AF"}}/>
          <input className="input-field" style={{paddingLeft:36, width:240}} placeholder="Search users..." value={search} onChange={e=>setSearch(e.target.value)}/>
        </div>
      </div>
      <div className="stat-card">
        <table className="table">
          <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Age Group</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>{filtered.map(u=>(
            <tr key={u.id}>
              <td style={{fontWeight:600}}>{u.name}</td>
              <td style={{fontSize:13, color:"#6B7280"}}>{u.email}</td>
              <td><span className="badge badge-blue" style={{textTransform:"capitalize"}}>{u.role.replace("_"," ")}</span></td>
              <td><span className={`badge ${u.age_group==="child"?"badge-gold":"badge-green"}`}>{u.age_group}</span></td>
              <td><span className={`badge ${u.banned?"badge-red":"badge-green"}`}>{u.banned?"banned":"active"}</span></td>
              <td style={{display:"flex", gap:6}}>
                <button style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"1px solid #D1D5DB",background:"#fff",cursor:"pointer"}}>View</button>
                <button onClick={()=>setUsers(users.map(x=>x.id===u.id?{...x,banned:!x.banned}:x))} style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:`1px solid ${u.banned?"#16A34A":"#EF4444"}`,background:"none",color:u.banned?"#16A34A":"#EF4444",cursor:"pointer"}}>{u.banned?"Unban":"Ban"}</button>
              </td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}

// ── ADMIN MODERATION ─────────────────────────────────────────────────────────
function AdminModeration() {
  const [items, setItems] = useState(contentItems);
  const approve = (id) => setItems(items.map(i=>i.id===id?{...i,status:"approved"}:i));
  const reject  = (id) => setItems(items.map(i=>i.id===id?{...i,status:"rejected"}:i));
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Content Moderation</h1>
      <div className="stat-card">
        <table className="table">
          <thead><tr><th>Title</th><th>Type</th><th>Uploader</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
          <tbody>{items.map(i=>(
            <tr key={i.id}>
              <td style={{fontWeight:600}}>{i.title}</td>
              <td><span className="badge badge-blue" style={{display:"inline-flex",alignItems:"center",gap:4}}><TypeIcon type={i.type}/>{i.type}</span></td>
              <td style={{color:"#6B7280", fontSize:13}}>{i.uploader}</td>
              <td><span className={`badge ${i.status==="approved"?"badge-green":i.status==="rejected"?"badge-red":"badge-gold"}`}>{i.status}</span></td>
              <td style={{fontSize:13, color:"#6B7280"}}>{i.date}</td>
              <td style={{display:"flex", gap:6}}>
                <button style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"none",background:"#F3F4F6",cursor:"pointer"}}><Eye size={12}/> Preview</button>
                {i.status==="pending"&&<>
                  <button onClick={()=>approve(i.id)} style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"none",background:"#DCFCE7",color:"#166534",cursor:"pointer"}}>✓ Approve</button>
                  <button onClick={()=>reject(i.id)} style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"none",background:"#FEE2E2",color:"#991B1B",cursor:"pointer"}}>✗ Reject</button>
                </>}
              </td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}

// ── ADMIN PROGRAMS ────────────────────────────────────────────────────────────
function AdminPrograms() {
  const [progs, setProgs] = useState(programs);
  return (
    <div style={{padding:32}} className="animate-fade">
      <div style={{display:"flex", justifyContent:"space-between", alignItems:"center", marginBottom:24}}>
        <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14"}}>Programs</h1>
        <button className="btn-gold">+ New Program</button>
      </div>
      <div className="stat-card">
        <table className="table">
          <thead><tr><th>Title</th><th>Level</th><th>Students</th><th>Published</th><th>Actions</th></tr></thead>
          <tbody>{progs.map(p=>(
            <tr key={p.id}>
              <td><div style={{fontWeight:600}}>{p.title}</div><div style={{fontSize:12,color:"#9CA3AF"}}>{p.juz}</div></td>
              <td><span className={`badge ${p.level==="free"?"badge-green":"badge-gold"}`}>{p.level}</span></td>
              <td style={{fontWeight:600, color:"#1B4332"}}>{p.students}</td>
              <td><span className={`badge ${p.published?"badge-green":"badge-red"}`}>{p.published?"live":"draft"}</span></td>
              <td style={{display:"flex", gap:6}}>
                <button style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"1px solid #D1D5DB",background:"#fff",cursor:"pointer"}}><Edit size={11}/> Edit</button>
                <button onClick={()=>setProgs(progs.map(x=>x.id===p.id?{...x,published:!x.published}:x))} style={{padding:"5px 10px",fontSize:11,borderRadius:6,border:"none",background:p.published?"#FEE2E2":"#DCFCE7",color:p.published?"#991B1B":"#166534",cursor:"pointer"}}>{p.published?"Unpublish":"Publish"}</button>
              </td>
            </tr>
          ))}</tbody>
        </table>
      </div>
    </div>
  );
}

// ── ADMIN REPORTS ─────────────────────────────────────────────────────────────
function AdminReports() {
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Reports</h1>
      <div style={{display:"grid", gridTemplateColumns:"1fr 1fr", gap:24}}>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16}}>Donations Summary</h3>
          {[["One-Time Donations","$3,240","+12% this month"],["Monthly Recurring","$1,580","42 active subscribers"],["Total Raised","$4,820","All time"]].map(([l,v,s])=>(
            <div key={l} style={{padding:"12px 0", borderBottom:"1px solid #F3F4F6"}}>
              <div style={{display:"flex", justifyContent:"space-between"}}>
                <span style={{fontSize:14, color:"#374151"}}>{l}</span>
                <span style={{fontWeight:700, color:"#1B4332"}}>{v}</span>
              </div>
              <div style={{fontSize:12, color:"#9CA3AF", marginTop:2}}>{s}</div>
            </div>
          ))}
          <button className="btn-gold" style={{marginTop:16, width:"100%"}}>Export CSV</button>
        </div>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16}}>Consent Records</h3>
          {[["Approved","247","badge-green"],["Pending","8","badge-gold"],["Denied","3","badge-red"]].map(([l,v,b])=>(
            <div key={l} style={{display:"flex", justifyContent:"space-between", alignItems:"center", padding:"12px 0", borderBottom:"1px solid #F3F4F6"}}>
              <span className={`badge ${b}`}>{l}</span>
              <span style={{fontWeight:700, fontSize:20, color:"#0A1F14"}}>{v}</span>
            </div>
          ))}
          <button className="btn-green" style={{marginTop:16, width:"100%"}}>View All</button>
        </div>
      </div>
    </div>
  );
}

// ── ACCOUNT SETTINGS ──────────────────────────────────────────────────────────
function AccountSettings({user}) {
  return (
    <div style={{padding:32}} className="animate-fade">
      <h1 style={{fontSize:28, fontWeight:800, color:"#0A1F14", marginBottom:24}}>Account Settings</h1>
      <div style={{display:"grid", gap:24, maxWidth:600}}>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>Profile</h3>
          <div style={{marginBottom:14}}><label style={{fontSize:13,fontWeight:600,display:"block",marginBottom:6,color:"#374151"}}>Full Name</label><input className="input-field" defaultValue={user?.name}/></div>
          <div style={{marginBottom:20}}><label style={{fontSize:13,fontWeight:600,display:"block",marginBottom:6,color:"#374151"}}>Language</label><select className="input-field"><option value="en">English</option><option value="ar">Arabic</option></select></div>
          <button className="btn-gold">Save Changes</button>
        </div>
        <div className="stat-card">
          <h3 style={{fontWeight:700, marginBottom:16, color:"#0A1F14"}}>Change Password</h3>
          <div style={{marginBottom:14}}><label style={{fontSize:13,fontWeight:600,display:"block",marginBottom:6,color:"#374151"}}>Current Password</label><input className="input-field" type="password" placeholder="••••••••"/></div>
          <div style={{marginBottom:14}}><label style={{fontSize:13,fontWeight:600,display:"block",marginBottom:6,color:"#374151"}}>New Password</label><input className="input-field" type="password" placeholder="••••••••"/></div>
          <div style={{marginBottom:20}}><label style={{fontSize:13,fontWeight:600,display:"block",marginBottom:6,color:"#374151"}}>Confirm Password</label><input className="input-field" type="password" placeholder="••••••••"/></div>
          <button className="btn-green">Update Password</button>
        </div>
        <div className="stat-card" style={{borderColor:"#FEE2E2"}}>
          <h3 style={{fontWeight:700, marginBottom:8, color:"#991B1B"}}>⚠️ Danger Zone</h3>
          <p style={{fontSize:14, color:"#6B7280", marginBottom:16}}>Export your data (GDPR) or permanently delete your account.</p>
          <div style={{display:"flex", gap:10}}>
            <button className="btn-green" style={{fontSize:13}}>📦 Export My Data</button>
            <button style={{padding:"12px 20px",borderRadius:10,border:"none",background:"#FEE2E2",color:"#991B1B",cursor:"pointer",fontWeight:700,fontSize:13}}>🗑 Delete Account</button>
          </div>
        </div>
      </div>
    </div>
  );
}

// ── APP ROOT ──────────────────────────────────────────────────────────────────
export default function App() {
  const [page, setPage] = useState("home");
  const [user, setUser] = useState(null);

  const navigate = (p) => {
    if(p==="dashboard" && user) { setPage("dashboard"); return; }
    setPage(p);
    window.scrollTo(0,0);
  };

  const login = (u) => { setUser(u); setPage("dashboard"); };
  const logout = () => { setUser(null); setPage("home"); };

  const showNav = !["login","register"].includes(page);

  return (
    <>
      <style>{css}</style>
      {showNav && <Navbar onNav={navigate} currentPage={page} user={user} onLogout={logout}/>}
      {page==="home"     && <Landing onNav={navigate}/>}
      {page==="programs" && <ProgramsPage onNav={navigate} user={user}/>}
      {page==="pricing"  && <PricingPage onNav={navigate}/>}
      {page==="donate"   && <DonatePage user={user}/>}
      {page==="login"    && <AuthPage type="login" onNav={navigate} onLogin={login}/>}
      {page==="register" && <AuthPage type="register" onNav={navigate} onLogin={login}/>}
      {page==="dashboard"&& user && <DashboardShell user={user} onNav={navigate} onLogout={logout}/>}
    </>
  );
}
